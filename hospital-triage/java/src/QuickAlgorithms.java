import java.util.Arrays;
import java.util.Random;

public class QuickAlgorithms {

    private static Random rand = new Random();


    public static void quickSort(Patient[] arr, boolean randomized) {
        quickSort(arr, 0, arr.length - 1, randomized);
    }

    public static Patient quickSelect(Patient[] arr, int k, boolean randomized) {
        Patient[] copy = Arrays.copyOf(arr, arr.length);
        return quickSelectHelper(copy, 0, copy.length - 1, k - 1, randomized);
    }

   

    private static void quickSort(Patient[] arr, int low, int high, boolean randomized) {
        if (low < high) {
            int p = partition(arr, low, high, randomized);
            quickSort(arr, low, p - 1, randomized);
            quickSort(arr, p + 1, high, randomized);
        }
    }

    private static Patient quickSelectHelper(Patient[] arr, int low, int high,
                                             int k, boolean randomized) {
        if (low == high) return arr[low];

        int p = partition(arr, low, high, randomized);
        int leftSize = p - low;

        if (k == leftSize)
            return arr[p];
        else if (k < leftSize)
            return quickSelectHelper(arr, low, p - 1, k, randomized);
        else
            return quickSelectHelper(arr, p + 1, high, k - leftSize - 1, randomized);
    }

    private static int partition(Patient[] arr, int low, int high, boolean randomized) {
        int pivotIndex = randomized
                ? low + rand.nextInt(high - low + 1)
                : medianOfThree(arr, low, high);

        swap(arr, pivotIndex, high);
        double pivot = arr[high].getSeverity();

        int i = low - 1;
        for (int j = low; j < high; j++) {
            if (arr[j].getSeverity() > pivot) {
                i++;
                swap(arr, i, j);
            }
        }

        swap(arr, i + 1, high);
        return i + 1;
    }

    private static int medianOfThree(Patient[] arr, int low, int high) {
        int mid = (low + high) / 2;

        double a = arr[low].getSeverity();
        double b = arr[mid].getSeverity();
        double c = arr[high].getSeverity();

        if ((a <= b && b <= c) || (c <= b && b <= a)) return mid;
        if ((b <= a && a <= c) || (c <= a && a <= b)) return low;
        return high;
    }

    private static void swap(Patient[] arr, int i, int j) {
        Patient temp = arr[i];
        arr[i] = arr[j];
        arr[j] = temp;
    }
}