import java.util.PriorityQueue;
import java.util.Scanner;
import java.util.Stack;

public class FlightReservationSystem {
   public static void main(String[] args) {
	   Scanner scan = new Scanner(System.in);
	   ReservationLinkedList reservationList = new ReservationLinkedList();
	 
	   PriorityQueue<Reservation> priorityQueue = new PriorityQueue<>(
               (r1, r2) -> Integer.compare(r2.getReservationNumber().length(), r1.getReservationNumber().length())
       );
      
       Stack<Reservation> canceledReservations = new Stack<>();
       
       Flight flight1 = new Flight ("MEA101", "BEIRUT", "KUWAIT CITY", 5);
       Flight flight2 = new Flight ("TK102", "ISTANBUL", "BANGKOK", 3);
       
       while(true) {
    	   System.out.println("\n=== Flight Reservation System ===");
    	   System.out.println("1. Display Flights");
    	   System.out.println("2. Make a Reservation");
    	   System.out.println("3. Search Reservation");
    	   System.out.println("4. Cancel Reservation");
    	   System.out.println("5. Display All Reservations");
    	   System.out.println("6. Display Canceled Reservations");
    	   System.out.println("7. Exit");
    	   System.out.print("Choose an option: ");
    	   int choice = scan.nextInt();
    	   scan.nextLine();
    	   
    	   switch (choice) {
    	   case 1:
    		   flight1.displayFlight();
    		   flight2.displayFlight();
    		   break;
    		   
    	   case 2:
    		   System.out.print("Enter Reservation Number: ");
    		   String reservationNumber = scan.nextLine();
    		   
    		   System.out.println("\nAvailable Flights:");
    		   flight1.displayFlight();
    		   flight2.displayFlight();
    		   
    		   System.out.print("Choose a flight (1 or 2): ");
    		   int flightChoice = scan.nextInt();
    		   scan.nextLine();
    		   
    		   Flight selectedFlight;
    		   if (flightChoice == 1) {
    			   selectedFlight = flight1;
    		   } else {
    			   selectedFlight = flight2;
    		   }
    		   
    		   if (!selectedFlight.checkAvailability()) {
    			   System.out.println("No Seats Available.");
    			   break;
    		   }
    		   
    		   System.out.print("Enter Customer ID: ");
    		   String customerId = scan.nextLine();
    		   System.out.print("Enter Customer Name: ");
    		   String customerName = scan.nextLine();
    		   System.out.print("Enter Customer Email: ");
    		   String customerEmail = scan.nextLine();
    		   
    		   Customer customer = new Customer(customerId, customerName, customerEmail);
    		   Reservation newReservation = new Reservation(reservationNumber, selectedFlight, customer);
    		   
    		   reservationList.insert(newReservation);
    		   priorityQueue.add(newReservation);
    		   
    		   System.out.println("\nReservation made successfully!");
    		   newReservation.displayReservation();
    		   break;
    		   
    	   case 3:
    		   System.out.println("Enter Reservation Number to search: ");
    		   String searchNumber = scan.nextLine();
    		   
    		   if (reservationList.search(searchNumber)) {
    			   System.out.println("Reservation found!");
    		   } else { 
    			   System.out.println("Reservation not found.");
    		   }
    		   break;
    		   
    	   case 4: 
    		   System.out.println("Enter Reservation Number to cancel: ");
    		   String cancelResNumber = scan.nextLine();
    		   
    		   Reservation removed = reservationList.delete(cancelResNumber);
    		   if (removed != null) {
    			   canceledReservations.push(removed);
    			   removed.getFlight().increaseSeats();
    			   System.out.println("Reservation cancelled successfully!.");
    		   } else {
    			   System.out.println("Reservation not found.");
    		   }
    		   break;
    		   
    	   case 5: 
    		   System.out.println("\nAll Reservations: ");
    		   reservationList.display();
    		   break;
    		   
    	   case 6:
    		   System.out.println("\nCanceled Reservations:");
    		   if (canceledReservations.isEmpty()) {
    			   System.out.println("No caneled reservations.");
    		   } else {
    			   for (Reservation canceled : canceledReservations) {
    				   canceled.displayReservation();
    			   }
    		   }
    		   break;
    		   
    	   case 7: 
    		   System.out.println("Exiting the system....");
    		   scan.close();
    		   return;
    		   
    	   default: 
    			System.out.println("Invalid choice! Please try again.");
    	     }
         }
     } 
 }
       