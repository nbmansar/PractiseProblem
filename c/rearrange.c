#include <stdio.h>

void rearrangeArray(int arr[], int size) {
    int temp[size];

    // Copy elements in the desired order to a temporary array
    for (int i = 0; i < size; i++) {
        temp[i] = arr[size - 1 - i];
        temp[i + size] = arr[i];
    }

}

int main() {
    int arr[] = {1, 2, 3, 4, 5, 6, 7};
    int size = sizeof(arr) / sizeof(arr[0]);

    printf("Original Array: ");
    for (int i = 0; i < size; i++) {
        printf("%d ", arr[i]);
    }

    rearrangeArray(arr, size);

    printf("\nRearranged Array: ");
    for (int i = 0; i < size; i++) {
        printf("%d ", arr[i]);
    }

    return 0;
}

