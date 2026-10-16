def analyze_frequency(text):
    frequency = {}

    for char in text:
        if char.isalpha():
            char = char.upper()
            frequency[char] = frequency.get(char, 0) + 1

    return frequency


def detect_cipher(text):
    freq = analyze_frequency(text)
    unique_letters = len(freq)

    if unique_letters < 10:
        return (
            "Caesar Cipher",
            "The ciphertext contains a small number of unique letters. "
            "This means that it is a simple shift pattern, which is Caesar cipher."
        )

    elif unique_letters <= 20:
        return (
            "Monoalphabetic Substitution Cipher",
            "The ciphertext shows moderate variation in letters. "
            "This means that the letters are replaced but patterns still exist, which is substitution ciphers."
        )

    else:
        return (
            "Transposition Cipher",
            "The ciphertext contains many different letters. "
            "This means that the letters are rearranged rather than replaced, which is transposition ciphers."
        )


def recommend_attack(cipher):
    if cipher == "Caesar Cipher":
        return "Use brute-force attack by trying all 25 possible shifts."

    elif cipher == "Monoalphabetic Substitution Cipher":
        return "Use frequency analysis by comparing common letters such as E, T, A, and O."

    else:
        return "Use structural analysis by rearranging positions and testing possible patterns."


def main():
    print("===Cipher Forensics and Attack Recommendation Engine===")

    text = input("Enter ciphertext: ")

    freq = analyze_frequency(text)
    cipher, explanation = detect_cipher(text)
    attack = recommend_attack(cipher)

    print("\n===Pattern Analysis===")
    print("Letter Frequency:", freq)
    print("Number of Unique Letters:", len(freq))

    print("\n===Estimated Encryption Family===")
    print("Detected Cipher:", cipher)

    print("\n===Explanation===")
    print(explanation)

    print("\n===Recommended Attack Strateg===")
    print(attack)


main()