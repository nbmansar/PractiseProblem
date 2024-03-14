#include<stdio.h>

struct tictac{

};

int main(){
	int a[3][3],count=0;int choice;int newCount=0;
	for(int i=1;i<=3;i++){
	for(int j=1;j<=3;j++){
		a[i][j] = count++;
	}
	}

	while(1){
	  for(int i=1;i<=3;i++){
        for(int j=1;j<=3;j++){
                printf("%d ",a[i][j]);
        }printf("\n");
        }
	  printf("Enter your choice: ");scanf("%d",&choice);

	   for(int i=1;i<=3;i++){
        for(int j=1;j<=3;j++){
                if(newCount++ == choice){
			if(a[i][j] != '*'){
				a[i][j] = '*';
			}else{
				printf("Please New choice");
			}	
		}
        }
        }

	}
	return 0;
}
