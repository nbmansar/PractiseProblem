import java.util.Map;

public class Numbers {

    public static void main(String[] args) {
        Map<String, String[]> numbers = new HashMap<>();

        numbers.put("nB10", new String[]{"Zero", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine"});
        numbers.put("nA10", new String[]{"Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen"});
        numbers.put("nZeros", new String[]{"Ten", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"});

        System.out.println(numbers);
    }
}

