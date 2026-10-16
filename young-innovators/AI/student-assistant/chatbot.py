import os
from openai import OpenAI
from dotenv import load_dotenv

load_dotenv()

API_KEY = os.getenv("OPENAI_KEY_CHILD")
if not API_KEY:
    raise SystemExit("OPENAI_KEY_CHILD in .env")

client = OpenAI(api_key=API_KEY)

SYSTEM_PROMPT = (
"You are Buddy, the Young Innovators learning assistant."

"Your audience is children aged 10-13."

"You MUST ONLY talk about:\n"

"- Explain everything in simple kid-friendly language.\n"
"- Use short sentences.\n"
"- Use examples children understand.\n"
"- Be encouraging and positive.\n"
"- Help children learn instead of just giving answers.\n"
"- If a child is confused, explain step-by-step.\n"
"- If the child asks about math, science, coding, reading, art, music, history, geography, school subjects, homework, projects, or learning topics, help them.\n\n"

"STRICT RULE:\n"

"If the question is NOT educational, do NOT answer it."

"Instead say something similar to:"

"I am your Young Innovators learning buddy. I can help with Learning subjects, coding, science, reading, math, projects, and learning activities. What would you like to learn today?"

"Do NOT discuss:"
"- dating"
"- romance"
"- adult topics"
"- violence"
"- illegal activities"
"- dangerous activities"

"Always redirect back to learning."

)

# Store conversation (memory)
conversation = [
    {"role": "system", "content": SYSTEM_PROMPT}
]

def ask_ai(text: str) -> str:
    try:
        conversation.append({"role": "user", "content": text})

        resp = client.responses.create(
            model="gpt-4o-mini",
            input=conversation
        )

        reply = (resp.output_text or "").strip()

        conversation.append({"role": "assistant", "content": reply})

        return reply

    except Exception as e:
        return f"Error: {str(e)}"


from flask import Flask, request, jsonify
from flask_cors import CORS

app = Flask(__name__)
CORS(app)

@app.route("/chat", methods=["POST"])
def chat():

    data = request.get_json()

    user_message = data.get("message", "")

    reply = ask_ai(user_message)

    return jsonify({
        "reply": reply
    })

if __name__ == "__main__":

    app.run(
        debug=True,
        port=5003
    )



