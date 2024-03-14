#include<stdio.h>
#define MAX 3

int main(){
	/*
	int matrix[MAX][MAX];
	int arr[MAX * MAX];
	int count=0;
	for(int i=0;i<MAX;i++){
		for(int j=0;j<MAX;j++){
			printf("Enter the [%d][%d] number",i,j);
			scanf("%d",&matrix[i][j]);
		}
	}
	for(int i=0;i<MAX;i++){
		for(int j=0;j<MAX;j++){
			printf("%d ",matrix[i][j]);
		}
		printf("\n");
	}

	printf("After \n");

	for(int i=0;i<MAX;i++){
                for(int j=0;j<MAX;j++){
                        printf("%d ",matrix[j][i]);
			arr[count]=matrix[j][i];
			count++;

                }
                printf("\n");
        }

	for(int i=0;i<MAX * MAX;i++){
		if(i%2==0){
		printf("%d ",arr[MAX * MAX - i]);
		}
	}
	*/

	 int n = 3; // Change this value to adjust the number of rows
    int counter = 1;

    for (int i = 1; i <= n; i++) {
        if (i % 2 == 1) {
            for (int j = 1; j <= n; j++) {
                printf("%d ", counter++);
            }
        } else {
            int temp = counter + n - 1;
            for (int j = n; j >= 1; j--) {
                printf("%d ", temp--);
            }
            counter += n;
        }
        printf("\n");
    }
}
