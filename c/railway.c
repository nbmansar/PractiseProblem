#include<stdio.h>
#include<string.h>

struct ticketDetail{
	char name[50];
	int Age;
};

char bookNewTicket(){
	struct ticketDetail ticketStructure;char employee_name[40];
	printf("\nEnter the Name");
	scanf("%s",employee_name);
	strcpy(employee_name,ticketStructure.name);
	printf("%s",ticketStructure.name);
}

char cancelTicket(int ticketId){


}

char editTicket(int ticketId){

}

int main(){
	int selectEntry,ticketId;
printf("Enter the number :\n 1.Book \n2.Cancel \n3.edit");
scanf("%d",&selectEntry);
switch(selectEntry){
	case 1:
		bookNewTicket();
		break;
	case 2:
		printf("Enter the TicketId to Cancel :");
		scanf("%d",&ticketId);
		cancelTicket(ticketId);
		break;
	case 3:
		printf("Enter the TicketId to Edit:");
		scanf("%d",&ticketId);
		editTicket(ticketId);
		break;
	default:
		printf("unknown Number");
		break;
}
}
