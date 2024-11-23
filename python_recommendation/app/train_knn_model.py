import pandas as pd
from surprise import KNNBasic, Dataset, Reader
from surprise.model_selection import train_test_split
from surprise import KNNBasic, accuracy
import joblib
import json

# قراءة الملفات
academic_student_details = pd.read_csv('academicstudentdetails.csv')
courses = pd.read_csv('courses.csv')
new_student_levels = pd.read_csv('newstudentlevels.csv')
plans = pd.read_csv('plans.csv')
plans_dtl = pd.read_csv('plansdtl.csv')
registration = pd.read_csv('registration.csv')
student_levels = pd.read_csv('studentlevels.csv')
students = pd.read_csv('Students.csv')
students_details = pd.read_csv('studentsdetails.csv')
from sklearn.preprocessing import LabelEncoder, MinMaxScaler
from sklearn.metrics.pairwise import cosine_similarity

# معاينة البيانات
print("Courses Data:")
print(courses.head())

print("\nStudents Data:")
print(students.head())

# دمج بيانات التسجيل مع الطلاب والدورات
merged_data = registration.merge(students, on='student_id') \
                          .merge(courses, on='course_id')

# اختيار الخصائص المهمة
data = merged_data[['student_id', 'course_id', 'category', 'difficulty', 'rating']]
print(data.head())

# تحويل الفئات النصية إلى أرقام
le = LabelEncoder()
data['category_encoded'] = le.fit_transform(data['category'])

# تجهيز الميزات
features = data[['difficulty', 'rating', 'category_encoded']].values

# تطبيع البيانات
scaler = MinMaxScaler()
normalized_features = scaler.fit_transform(features)

# حساب التشابه
similarity_matrix = cosine_similarity(normalized_features)

# توصية بناءً على تشابه الدورات
def recommend_courses(student_id, k=5):
    # استخراج الدورات التي حضرها الطالب
    student_courses = data[data['student_id'] == student_id]['course_id'].values

    # تحديد الدورات التي لم يحضرها
    unvisited_courses = data[~data['course_id'].isin(student_courses)]

    # حساب متوسط التشابه للدورات التي حضرها الطالب
    course_indices = [i for i, course_id in enumerate(data['course_id']) if course_id in student_courses]
    similarity_scores = similarity_matrix[course_indices].mean(axis=0)

    # إضافة درجات التشابه إلى الدورات التي لم يحضرها الطالب
    unvisited_courses = unvisited_courses.copy()
    unvisited_courses['similarity'] = unvisited_courses['course_id'].apply(
        lambda x: similarity_scores[data[data['course_id'] == x].index[0]]
    )

    # ترتيب الدورات بناءً على التشابه
    recommended_courses = unvisited_courses.sort_values('similarity', ascending=False).head(k)
    return recommended_courses

# تنفيذ التوصيات
student_id = 1
recommended = recommend_courses(student_id)
print("Recommended Courses for Student:", student_id)
print(recommended[['course_id', 'category', 'similarity']])

def display_recommendations(recommended):
    print("\n--- Recommended Courses ---")
    for _, row in recommended.iterrows():
        print(f"Course ID: {row['course_id']}, Category: {row['category']}, Similarity: {row['similarity']:.2f}")

# عرض النتائج
display_recommendations(recommended)

# تصدير التوصيات إلى ملف JSON
recommended.to_json('recommendations.json', orient='records')
def export_recommendations_to_json(recommendations, output_file='recommendations.json'):
    recommendations_data = [{'course_id': rec.iid, 'predicted_rating': rec.est} for rec in recommendations]
    with open(output_file, 'w') as f:
        json.dump(recommendations_data, f)

export_recommendations_to_json(recommendations)

# اختبار KNN
student_ids = data['student_id'].unique()
for student_id in student_ids:
    recommended = recommend_courses(student_id)
    display_recommendations(recommended)



########################
# تحديد الحدود للدرجات
reader = Reader(rating_scale=(0, 100))
data_for_model = Dataset.load_from_df(data[['student_id', 'course_id', 'grade']], reader)

# تقسيم البيانات للتدريب والاختبار
trainset, testset = train_test_split(data_for_model, test_size=0.2, random_state=42)

# إعداد النموذج KNN وتدريبه
sim_options = {'name': 'cosine', 'user_based': True}  # استخدم Cosine للتشابه بين الطلاب
model = KNNBasic(sim_options=sim_options)
model.fit(trainset)

# التقييم باستخدام RMSE
predictions = model.test(testset)
rmse = accuracy.rmse(predictions)
print(f'Root Mean Squared Error: {rmse}')
mae = accuracy.mae(predictions)
print(f'Mean Absolute Error: {mae}')

# حفظ النموذج
joblib.dump(model, 'knn_course_recommendation_model.pkl')

print(f'Training set: {len(trainset.all_users())} users, {len(trainset.all_items())} courses')



################
# تحميل النموذج
model = joblib.load('knn_course_recommendation_model.pkl')

# التوصية لدورات لمستخدم معين
def get_recommendations(user_id, courses, n=5):
    # توقع التقييم لجميع الدورات
    course_ids = courses['course_id'].unique()
    predictions = [model.predict(user_id, course_id) for course_id in course_ids]

# ترتيب التوصيات بناءً على التقييم المتوقع
    sorted_predictions = sorted(predictions, key=lambda x: x.est, reverse=True)
    return sorted_predictions[:n]

# عرض التوصيات
user_id = 1
recommendations = get_recommendations(user_id, data)
print("Recommendations:")
for rec in recommendations:
    print(f"Course ID: {rec.iid}, Predicted Rating: {rec.est}")

# تصدير التوصيات إلى JSON
def export_recommendations_to_json(recommendations, output_file='recommendations.json'):
    recommendations_data = [{'course_id': rec.iid, 'predicted_rating': rec.est} for rec in recommendations]
    with open(output_file, 'w') as f:
        json.dump(recommendations_data, f)

export_recommendations_to_json(recommendations)


# إعداد البيانات
reader = Reader(rating_scale=(0, 100))
data = Dataset.load_from_df(data[['student_id', 'course_id', 'grade']], reader)

# تقسيم البيانات
trainset, testset = train_test_split(data, test_size=0.2)

# تدريب النموذج
sim_options = {'name': 'cosine', 'user_based': True}  # تشابه المستخدمين
model = KNNBasic(sim_options=sim_options)
model.fit(trainset)

# تقييم النموذج
predictions = model.test(testset)
rmse = accuracy.rmse(predictions)
print(f'Root Mean Squared Error: {rmse}')

# حفظ النموذج إلى ملف .pkl
joblib.dump(model, 'knn_course_recommendation_model.pkl')
print("Model saved as knn_course_recommendation_model.pkl")

# تحميل النموذج
model = joblib.load('knn_course_recommendation_model.pkl')
print("Model loaded successfully!")

# تحميل النموذج عند بدء تشغيل Flask
model = joblib.load('knn_course_recommendation_model.pkl')



#model = joblib.load('knn_course_recommendation_model.pkl')
#model = joblib.load('knn_course_recommendation_model.pkl')
