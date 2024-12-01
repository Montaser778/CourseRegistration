from flask import Flask, request, jsonify
import joblib
from flask import Flask, jsonify, request
from recommend_course import load_model, get_recommendations
from flask_caching import Cache

app = Flask(__name__)
model = joblib.load('knn_course_recommendation_model.pkl')  # تحميل النموذج المحفوظ

cache = Cache(app, config={'CACHE_TYPE': 'SimpleCache'})

# إعداد الكاش
@app.route('/recommend', methods=['POST'])
@cache.cached(timeout=60, query_string=True)
def recommend():
    data = request.json  # بيانات JSON المرسلة من Laravel
    user_id = data['user_id']
    courses = data['courses'] # قائمة الدورات المتاحة
    recommendations = [
        {'course_id': course['course_id'], 'predicted_rating': 4.5}
        for course in courses
    ]
    return jsonify(recommendations)

if __name__ == '__main__':
    app.run(debug=True)

    recommendations = get_recommendations(model, user_id, courses)
    return jsonify(recommendations)

    # استلام بيانات الطالب
    student_id = request.json['student_id']

    # إنشاء توصيات من خلال النموذج
    all_courses = set(course_id for (_, course_id, _) in model.trainset.all_ratings())
    enrolled_courses = set(model.trainset.ur[student_id])
    courses_to_recommend = all_courses - enrolled_courses

    recommendations = []
    for course_id in courses_to_recommend:
        est = model.predict(student_id, course_id).est
        recommendations.append((course_id, est))

    # ترتيب النتائج واختيار أفضل التوصيات
    recommendations.sort(key=lambda x: x[1], reverse=True)
    top_recommendations = [course for course, _ in recommendations[:5]]

    return jsonify(top_recommendations)

# تنفيذ التوصيات
def get_recommendations(user_id, courses):
    predictions = [model.predict(user_id, course['course_id']) for course in courses]
    sorted_predictions = sorted(predictions, key=lambda x: x.est, reverse=True)
    return [{'course_id': pred.iid, 'predicted_rating': pred.est} for pred in sorted_predictions[:5]]

# نقطة النهاية لتقديم التوصيات
@app.route('/recommend', methods=['POST'])
@cache.cached(timeout=60, query_string=True)  # الكاش لمدة 60 ثانية
def recommend():
    try:
        # استلام البيانات من الطلب
        data = request.json
        user_id = data['user_id']
        courses = data['courses']

# تنفيذ التوصيات
        recommendations = get_recommendations(model, user_id, courses)
        return jsonify(recommendations)
    except Exception as e:
        return jsonify({'error': str(e)}), 500

    @cache.cached(timeout=60, key_prefix=lambda: f"user_{request.json['user_id']}")

# تعريف API للتوصيات
@app.route('/recommend', methods=['POST'])
def recommend():
    data = request.json  # بيانات JSON المرسلة من Laravel
    user_id = data['user_id']
    courses = data['courses']  # قائمة الدورات المتاحة
    recommendations = get_recommendations(user_id, courses)
    return jsonify(recommendations)

if __name__ == '__main__':
    app.run(port=5000, debug=True)

