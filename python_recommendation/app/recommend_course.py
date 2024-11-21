import pickle
import sys
# recommend_course.py
import joblib

# تحميل النموذج
def load_model():
    return joblib.load('knn_course_recommendation_model.pkl')

# تنفيذ التوصيات
def get_recommendations(model, user_id, courses):
    predictions = [model.predict(user_id, course['course_id']) for course in courses]
    sorted_predictions = sorted(predictions, key=lambda x: x.est, reverse=True)
    return [{'course_id': pred.iid, 'predicted_rating': pred.est} for pred in sorted_predictions[:5}]

# تحميل النموذج المدرب
with open('knn_recommendation_model.pkl', 'rb') as file:
    model = pickle.load(file)

try:
    # قراءة المدخلات من سطر الأوامر
    student_id = int(sys.argv[1])
    course_id = int(sys.argv[2])
    difficulty = int(sys.argv[3])

    # تحقق من المدخلات إذا كانت تتوافق مع توقعات النموذج
    recommendation = model.predict([[student_id, course_id, difficulty]])
    print(f"Recommended Grade or Outcome: {recommendation[0]}")

except IndexError:
    print("Error: Please provide student_id, course_id, and difficulty as arguments.")
except ValueError:
    print("Error: All inputs must be integers (student_id, course_id, difficulty).")
except Exception as e:
    print(f"An error occurred: {e}")
