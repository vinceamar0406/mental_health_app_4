from flask import Flask, request, jsonify
import joblib
import os
import numpy as np

app = Flask(__name__)

# Load the TF-IDF vectorizer and trained models
vectorizer_path = os.path.join(os.path.dirname(__file__), "tfidf_vectorizer.pkl")
model_path = os.path.join(os.path.dirname(__file__), "random_forest_weighted.pkl")

vectorizer = joblib.load(vectorizer_path)
model = joblib.load(model_path)

# Define class labels (Ensure this matches your training labels)
class_labels = {
    0: "Anxiety",
    1: "Depression",
    2: "PTSD",
    3: "Stress-Related Disorder",
    4: "Substance Use Disorder",
    5: "Eating Disorder",
    6: "Self-Harm Challenges",
    7: "Attention Issues",
    8: "Normal",  # Add additional labels
    9: "Bipolar",
    10: "Suicidal",
    11: "Stress",
    12: "Personality disorder"
}

@app.route("/predict", methods=["POST"])
def predict():
    try:
        data = request.json
        responses = data.get("responses", [])

        # Validate that there are enough responses
        if not responses or len(responses) < 5:
            return jsonify({"error": "Invalid input. Expected at least 5 responses."}), 400

        # Combine the responses into a single string of text
        combined_text = " ".join(responses)

        # Transform the text using the loaded TF-IDF vectorizer
        transformed_input = vectorizer.transform([combined_text])

        # Get the predicted class probabilities
        probabilities = model.predict_proba(transformed_input)[0]  # Get probabilities for each class
        predicted_index = np.argmax(probabilities)  # Index of highest probability class
        predicted_condition = class_labels.get(predicted_index, "Unknown Condition")

        # Get the top 3 predictions and their confidence scores
        top_indices = np.argsort(probabilities)[::-1][:3]  # Sort probabilities to get top 3
        top_conditions = [
            {"condition": class_labels[i], "confidence": round(probabilities[i] * 100, 2)}
            for i in top_indices
        ]

        # Return the prediction result as a JSON response
        return jsonify({
            "predicted_condition": predicted_condition,
            "confidence_score": round(probabilities[predicted_index] * 100, 2),
            "top_3_predictions": top_conditions
        })

    except Exception as e:
        return jsonify({"error": str(e)}), 500

if __name__ == "__main__":
    app.run(debug=True, host="0.0.0.0", port=5000)
