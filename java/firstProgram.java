class Ansar {
    public static int Ariff() {
        return 43;
    }

    public static boolean Akash() {
        return true;
    }
}

class Ak {
    public static int names(String name) {
        int len = name.length();
        return len;
    }
}

public class firstProgram {
    public static void main(String[] args) {
        Ansar one = new Ansar();
        int v = one.Ariff();
        if (one.Akash()) {
            System.out.println("success");
        }
        System.out.println("Ansar" + v);

        Ak len = new Ak();
        int lent = len.names("aAA");

        System.out.println("AAAA" + lent);
    }
}

