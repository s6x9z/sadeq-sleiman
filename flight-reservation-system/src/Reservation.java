
public class Reservation {
	private String reservationNumber;
	private Flight flight;
	private Customer customer;
	
	public Reservation(String reservationNumber, Flight flight, Customer customer) {
		this.reservationNumber = reservationNumber;
		this.flight = flight;
		this.customer = customer;
		flight.decreaseSeats();
	}
	
	public String getReservationNumber() {
		return reservationNumber;
	}
	
	public Flight getFlight() {
		return flight;
	}
	
	public void displayReservation() {
		System.out.println("Reservation Number: " + reservationNumber);
		customer.displayCustomer();
		flight.displayFlight();
	}

}
