import java.util.Arrays;
import java.util.Random;
import java.util.Scanner;

public class TriageSystem {

    private static Random rand = new Random();

    public static void main(String[] args) {

        Scanner sc = new Scanner(System.in);
        Patient[] patients = null;

        System.out.println("=== Hospital Emergency Triage System ===");

      
        while (patients == null) {
            System.out.println("\nSelect input method:");
            System.out.println("1. Manual patient entry");
            System.out.println("2. Randomly generate patients");
            System.out.println("3. Exit");
            System.out.print("> ");

            String c = sc.nextLine().trim();

            switch (c) {
                case "1":
                    patients = manualInput(sc);
                    break;

                case "2":
                    System.out.print("How many patients? ");
                    try {
                        int n = Integer.parseInt(sc.nextLine());
                        if (n > 0) {
                            patients = randomPatients(n);
                            System.out.println("Random patients generated.");
                        } else {
                            System.out.println("Number must be positive.");
                        }
                    } catch (NumberFormatException e) {
                        System.out.println("Invalid number.");
                    }
                    break;

                case "3":
                    System.out.println("Goodbye.");
                    sc.close();
                    return;

                default:
                    System.out.println("Invalid choice. Try again.");
            }
        }

        
        while (true) {
            System.out.println("\nMain Menu");
            System.out.println("1. Show patients");
            System.out.println("2. Find k-th most critical patient (QuickSelect)");
            System.out.println("3. Sort patients by severity (QuickSort)");
            System.out.println("4. Compare randomized vs deterministic performance");
            System.out.println("5. Search patient by name");
            System.out.println("6. Show statistics");
            System.out.println("7. Triage help");
            System.out.println("8. Exit");
            System.out.print("> ");

            String op = sc.nextLine().trim();

            switch (op) {
                case "1":
                    printPatients(patients);
                    break;

                case "2":
                    handleQuickSelect(sc, patients);
                    break;

                case "3":
                    handleQuickSort(sc, patients);
                    break;

                case "4":
                    compareAlgorithms(patients);
                    break;

                case "5":
                    searchPatients(patients, sc);
                    break;

                case "6":
                    showStatistics(patients);
                    break;

                case "7":
                    showHelp();
                    break;

                case "8":
                    System.out.println("Goodbye.");
                    return;

                default:
                    System.out.println("Invalid option.");
            }
        }
    }


    private static Patient[] manualInput(Scanner sc) {
        System.out.print("How many patients? ");
        int n;
        try {
            n = Integer.parseInt(sc.nextLine());
        } catch (NumberFormatException e) {
            System.out.println("Invalid number. Using 1.");
            n = 1;
        }

        if (n <= 0) n = 1;

        Patient[] arr = new Patient[n];

        for (int i = 0; i < n; i++) {
            System.out.println("\nPatient #" + (i + 1));
            System.out.print("Name: ");
            String name = sc.nextLine().trim();
            if (name.isEmpty()) name = "Patient_" + (i + 1);

            System.out.print("Severity (0–100): ");
            double s;
            try {
                s = Double.parseDouble(sc.nextLine());
            } catch (Exception e) {
                System.out.println("Invalid severity. Using 50.");
                s = 50;
            }

            arr[i] = new Patient(name, s);
        }

        return arr;
    }

    private static Patient[] randomPatients(int n) {
        Patient[] arr = new Patient[n];
        for (int i = 0; i < n; i++) {
            String name = "Patient_" + (i + 1);
            double s = Math.round(rand.nextDouble() * 10000.0) / 100.0;
            arr[i] = new Patient(name, s);
        }
        return arr;
    }

   

    private static int getTriageLevel(double severity) {
        if (severity >= 75) return 1;
        if (severity >= 50 && severity <= 74) return 2;
        if (severity >= 25 && severity <= 49) return 3;
        return 4;
    }

    private static String describeLevel(int level) {
        switch (level) {
            case 1: return "Critical – immediate treatment";
            case 2: return "High priority – see soon";
            case 3: return "Normal – can wait";
            default: return "Minor/Unknown – low urgency";
        }
    }

   

    private static void printPatients(Patient[] arr) {
        if (arr.length == 0) {
            System.out.println("No patients.");
            return;
        }

        System.out.println("\n--- Patient List ---");
        for (int i = 0; i < arr.length; i++) {
            Patient p = arr[i];
            int level = getTriageLevel(p.getSeverity());
            System.out.println(
                (i + 1) + ". " + p.getName() +
                " | Severity: " + p.getSeverity() +
                " | Level " + level + " (" + describeLevel(level) + ")"
            );
        }
    }

    private static void handleQuickSelect(Scanner sc, Patient[] arr) {
        System.out.print("Enter k: ");
        int k;
        try {
            k = Integer.parseInt(sc.nextLine());
        } catch (Exception e) {
            System.out.println("Invalid.");
            return;
        }

        if (k < 1 || k > arr.length) {
            System.out.println("k must be between 1 and " + arr.length);
            return;
        }

        System.out.println("Choose pivot strategy:");
        System.out.println("1. Randomized pivot");
        System.out.println("2. Deterministic pivot (Median-of-Three)");
        System.out.print("> ");
        String pivotChoice = sc.nextLine().trim();
        boolean rnd = pivotChoice.equals("1");

        long t0 = System.nanoTime();
        Patient p = QuickAlgorithms.quickSelect(arr, k, rnd);
        long t1 = System.nanoTime();

        int lvl = getTriageLevel(p.getSeverity());
        System.out.println("\nResult: " + p.getName());
        System.out.println("Severity: " + p.getSeverity());
        System.out.println("Triage Level " + lvl + " (" + describeLevel(lvl) + ")");
        System.out.println("Time: " + ((t1 - t0) / 1_000_000.0) + " ms");
    }

    private static void handleQuickSort(Scanner sc, Patient[] arr) {
        System.out.println("Choose pivot strategy:");
        System.out.println("1. Randomized pivot");
        System.out.println("2. Deterministic pivot (Median-of-Three)");
        System.out.print("> ");
        String pivotChoice = sc.nextLine().trim();
        boolean rnd = pivotChoice.equals("1");

        Patient[] copy = Arrays.copyOf(arr, arr.length);

        long t0 = System.nanoTime();
        QuickAlgorithms.quickSort(copy, rnd);
        long t1 = System.nanoTime();

        printPatients(copy);
        System.out.println("\nSort time: " + ((t1 - t0) / 1_000_000.0) + " ms");
    }

    private static void searchPatients(Patient[] arr, Scanner sc) {
        System.out.print("Enter name keyword: ");
        String key = sc.nextLine().toLowerCase().trim();

        boolean found = false;
        for (Patient p : arr) {
            if (p.getName().toLowerCase().contains(key)) {
                int lvl = getTriageLevel(p.getSeverity());
                System.out.println(
                    p.getName() +
                    " | Severity: " + p.getSeverity() +
                    " | Level " + lvl + " (" + describeLevel(lvl) + ")"
                );
                found = true;
            }
        }

        if (!found)
            System.out.println("No matching patients.");
    }

    private static void showStatistics(Patient[] arr) {
        int[] levels = new int[5];
        double sum = 0;

        for (Patient p : arr) {
            int lvl = getTriageLevel(p.getSeverity());
            levels[lvl]++;
            sum += p.getSeverity();
        }

        System.out.println("\n--- Statistics ---");
        System.out.println("Total patients: " + arr.length);
        System.out.println("Critical (1): " + levels[1]);
        System.out.println("High (2): " + levels[2]);
        System.out.println("Normal (3): " + levels[3]);
        System.out.println("Minor (4): " + levels[4]);
        System.out.printf("Average severity: %.2f\n", (arr.length > 0 ? sum / arr.length : 0));
    }

    private static void showHelp() {
        System.out.println("\n--- Triage Help ---");
        System.out.println("1 = Critical (severity ≥ 75)");
        System.out.println("2 = High Priority (severity ≥ 50)");
        System.out.println("3 = Normal Priority (severity ≥ 25)");
        System.out.println("4 = Minor (severity < 25)");
    }

    private static void compareAlgorithms(Patient[] arr) {
        int trials = 5;
        double qsRand = 0, qsDet = 0, sortRand = 0, sortDet = 0;

        for (int i = 0; i < trials; i++) {
            int k = 1 + rand.nextInt(arr.length);

            long t0 = System.nanoTime();
            QuickAlgorithms.quickSelect(arr, k, true);
            long t1 = System.nanoTime();
            qsRand += (t1 - t0);

            t0 = System.nanoTime();
            QuickAlgorithms.quickSelect(arr, k, false);
            t1 = System.nanoTime();
            qsDet += (t1 - t0);

            Patient[] c1 = Arrays.copyOf(arr, arr.length);
            t0 = System.nanoTime();
            QuickAlgorithms.quickSort(c1, true);
            t1 = System.nanoTime();
            sortRand += (t1 - t0);

            Patient[] c2 = Arrays.copyOf(arr, arr.length);
            t0 = System.nanoTime();
            QuickAlgorithms.quickSort(c2, false);
            t1 = System.nanoTime();
            sortDet += (t1 - t0);
        }

        System.out.println("\n=== Average Times (ms) ===");
        System.out.println("QuickSelect (random):      " + (qsRand / trials / 1_000_000.0));
        System.out.println("QuickSelect (deterministic): " + (qsDet / trials / 1_000_000.0));
        System.out.println("QuickSort (random):        " + (sortRand / trials / 1_000_000.0));
        System.out.println("QuickSort (deterministic): " + (sortDet / trials / 1_000_000.0));
    }
}