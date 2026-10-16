public class Patient {
    private String name;
    private double severity;

    public Patient(String name, double severity) {
        this.name = name;
        this.severity = severity;
    }

    public String getName() {
        return name;
    }

    public double getSeverity() {
        return severity;
    }


    public String toString() {
        return name + " (severity = " + severity + ")";
    }
}