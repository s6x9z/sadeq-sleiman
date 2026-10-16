def get_triage_level(symptom):
 
    symptom = symptom.lower()

    # Symptom keyword groups
    critical = ["chest pain", "unconscious", "severe bleeding", "stroke", "heart attack", "severe burns", "not breathing"]
    high = ["high fever", "difficulty breathing", "fracture", "vomiting", "severe headache", "deep cut", "asthma"]
    normal = ["cough", "headache", "stomach pain", "sore throat", "back pain", "minor cut", "cold", "flu"]

    # Check if any keyword appears inside the symptom text
    for word in critical:
        if word in symptom:
            return 1
    for word in high:
        if word in symptom:
            return 2
    for word in normal:
        if word in symptom:
            return 3

    # If nothing matches, we call it unknown/minor
    return 4


def describe_level(level):
  
    if level == 1:
        return "Critical – see doctor immediately"
    elif level == 2:
        return "High priority – should be seen very soon"
    elif level == 3:
        return "Normal priority – can wait"
    else:
        return "Unknown/Minor – needs nurse assessment"


# ----------- Patient input, validation, and editing -------------

def input_age():
 
    while True:
        age = input("Enter patient age: ").strip()
        if age.isdigit():
            age = int(age)
            if 0 < age < 100:
                return age
        print("Invalid age. Please enter a valid number between 1 and 100.")


def input_non_empty(prompt):
   
    while True:
        text = input(prompt).strip()
        if text != "":
            return text
        print("Input cannot be empty. Please try again.")


def add_patient(patients, next_id):
  
    print("\n[Add Patient]")
    name = input_non_empty("Enter patient name: ")
    age = input_age()
    gender = input_non_empty("Enter patient gender (M/F): ")
    symptom = input_non_empty("Enter main symptom: ")

    level = get_triage_level(symptom)

    patient = {
        "id": next_id,
        "name": name,
        "age": age,
        "gender": gender,
        "symptom": symptom,
        "level": level
    }

    patients.append(patient)
    print(f"\nPatient #{next_id} '{name}' added with Triage Level {level} ({describe_level(level)}).\n")
    return next_id + 1


def edit_patient(patients):
   
    print("\n[Edit Patient Info]")
    if not patients:
        print("No patients to edit.\n")
        return

    id_text = input("Enter the ID of the patient to edit: ").strip()
    if not id_text.isdigit():
        print("Invalid ID.\n")
        return
    pid = int(id_text)

    for patient in patients:
        if patient["id"] == pid:
            print(f"Editing patient #{patient['id']} - {patient['name']}")
            new_name = input(f"New name (or press Enter to keep '{patient['name']}'): ").strip()
            new_age = input("New age (or press Enter to keep current): ").strip()
            new_gender = input(f"New gender (or press Enter to keep '{patient['gender']}'): ").strip()
            new_symptom = input(f"New symptom (or press Enter to keep '{patient['symptom']}'): ").strip()

            if new_name:
                patient["name"] = new_name
            if new_age:
                if new_age.isdigit() and 0 < int(new_age) < 100:
                    patient["age"] = int(new_age)
                else:
                    print("Invalid age input; keeping old age.")
            if new_gender:
                patient["gender"] = new_gender
            if new_symptom:
                patient["symptom"] = new_symptom
                patient["level"] = get_triage_level(new_symptom)

            print(f"Patient #{patient['id']} info updated.\n")
            return

    print(f"No patient found with ID {pid}.\n")


def discharge_patient(patients):

    print("\n[Discharge Patient]")
    if not patients:
        print("No patients to discharge.\n")
        return

    id_text = input("Enter the ID of the patient to discharge: ").strip()
    if not id_text.isdigit():
        print("Invalid ID.\n")
        return
    pid = int(id_text)

    for i, patient in enumerate(patients):
        if patient["id"] == pid:
            discharged = patients.pop(i)
            print(f"Patient #{discharged['id']} '{discharged['name']}' discharged successfully.\n")
            return

    print(f"No patient found with ID {pid}.\n")


# ----------- Viewing, searching, sorting, and stats -------------

def view_patients(patients):
   
    print("\n[View Patients]")
    if not patients:
        print("No patients currently waiting.\n")
        return

    print("\n--- Waiting List (Sorted by Priority and Age) ---")

    # Sort by level (1 = highest priority), then by age descending (older first)
    sorted_patients = sorted(patients, key=lambda x: (x["level"], -x["age"]))

    for p in sorted_patients:
        print(
            f"ID: {p['id']} | Name: {p['name']} | Age: {p['age']} | Gender: {p['gender']} | "
            f"Symptom: {p['symptom']} | Level: {p['level']} ({describe_level(p['level'])})"
        )

    print()


def view_by_level(patients):
  
    print("\n[View Patients by Level]")
    if not patients:
        print("No patients currently waiting.\n")
        return

    level_text = input("Enter triage level to view (1-4): ").strip()
    if not level_text.isdigit():
        print("Invalid level.\n")
        return
    level = int(level_text)

    if level < 1 or level > 4:
        print("Level must be between 1 and 4.\n")
        return

    filtered = [p for p in patients if p["level"] == level]

    if not filtered:
        print(f"No patients with triage level {level}.\n")
        return

    print(f"\nPatients with triage level {level} ({describe_level(level)}):")
    for p in filtered:
        print(
            f"ID: {p['id']} | Name: {p['name']} | Age: {p['age']} | Gender: {p['gender']} | "
            f"Symptom: {p['symptom']}"
        )
    print()


def search_patients(patients):
    
    print("\n[Search Patients]")
    if not patients:
        print("No patients to search.\n")
        return

    keyword = input("Enter name or symptom keyword to search: ").strip().lower()
    if not keyword:
        print("Search keyword cannot be empty.\n")
        return

    found = [
        p for p in patients
        if keyword in p["name"].lower() or keyword in p["symptom"].lower()
    ]

    if found:
        print(f"\nFound {len(found)} patient(s) matching '{keyword}':")
        for p in found:
            print(
                f"ID: {p['id']} | Name: {p['name']} | Age: {p['age']} | Gender: {p['gender']} | "
                f"Symptom: {p['symptom']} | Level: {p['level']} ({describe_level(p['level'])})"
            )
        print()
    else:
        print(f"No patients found matching '{keyword}'.\n")


def show_statistics(patients):
    
    print("\n[Statistics]")
    total = len(patients)
    level_counts = {1: 0, 2: 0, 3: 0, 4: 0}

    for p in patients:
        level_counts[p["level"]] += 1

    if total > 0:
        average_age = sum(p["age"] for p in patients) / total
    else:
        average_age = 0

    print(f"Total patients waiting: {total}")
    print(f"Critical (Level 1): {level_counts[1]}")
    print(f"High Priority (Level 2): {level_counts[2]}")
    print(f"Normal Priority (Level 3): {level_counts[3]}")
    print(f"Unknown/Minor (Level 4): {level_counts[4]}")
    print(f"Average age: {average_age:.1f} years\n")


def show_help():
  
    print("\n[Triage Help]")
    print("Level 1 (Critical): Life-threatening conditions, must be seen immediately.")
    print("    Examples: chest pain, stroke, severe bleeding, not breathing.")
    print("Level 2 (High Priority): Serious but not immediately life-threatening.")
    print("    Examples: high fever, bad fracture, difficulty breathing.")
    print("Level 3 (Normal Priority): Stable conditions that can safely wait.")
    print("    Examples: cough, mild headache, stomach pain, sore throat.")
    print("Level 4 (Unknown/Minor): Minor or unclear problems, needs nurse check.\n")


# ----------- Main program flow -------------

def main():
    print("Welcome to the Hospital Emergency Triage System")
    patients = []
    next_id = 1  

    while True:
        print("=========================================")
        print("1. Add Patient")
        print("2. View Waiting List")
        print("3. View Patients by Triage Level")
        print("4. Search Patients")
        print("5. Edit Patient Info")
        print("6. Discharge Patient")
        print("7. Show Statistics")
        print("8. Triage Help")
        print("9. Exit")
        print("=========================================")

        choice = input("Enter your choice (1-9): ").strip()

        if choice == "1":
            next_id = add_patient(patients, next_id)
        elif choice == "2":
            view_patients(patients)
        elif choice == "3":
            view_by_level(patients)
        elif choice == "4":
            search_patients(patients)
        elif choice == "5":
            edit_patient(patients)
        elif choice == "6":
            discharge_patient(patients)
        elif choice == "7":
            show_statistics(patients)
        elif choice == "8":
            show_help()
        elif choice == "9":
            print("Exiting system. Goodbye!")
            break
        else:
            print("\nInvalid choice. Try again.\n")


# Run the program
if __name__ == "__main__":
    main()