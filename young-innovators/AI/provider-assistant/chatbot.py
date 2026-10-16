import os
from dotenv import load_dotenv
from openai import OpenAI

# Load environment variables
load_dotenv()

API_KEY = os.getenv("OPENAI_KEY_PROVIDER")
if not API_KEY:
    raise SystemExit("Missing OPENAI_KEY_PROVIDER in .env")

client = OpenAI(api_key=API_KEY)

# Stronger system prompt
SYSTEM_PROMPT = (
    "You are the official AI assistant of the Young Innovators platform for CONTENT PROVIDERS (teachers). "
    "Your ONLY job is to help teachers convert educational PDF content into interactive learning games for children aged 10–13.\n\n"
    
    "You MUST ONLY talk about:\n"
    "- Lesson structure\n"
    "- Extracting key concepts from PDFs\n"
    "- Game design (matching, puzzles, story-based)\n"
    "- Difficulty levels\n"
    "- Number of levels\n"
    "- Creating structured game plans\n\n"
    
    "STRICT RULE:\n"
    "If the user asks anything unrelated, respond EXACTLY with:\n"
    "\"I am only designed to assist with converting educational content into learning games on this platform.\""
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
        port=5004
    )
