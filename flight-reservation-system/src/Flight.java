
public class Flight {
	private String flightNumber;
	private String departure;
	private String destination;
	private int availableSeats;
	
	public Flight(String flightNumber, String departure, String destination, int availableSeats) {
		this.flightNumber = flightNumber;
		this.departure = departure;
		this.destination = destination;
		this.availableSeats = availableSeats;
	}
	
	public String getFlightNumber() {
		return flightNumber;
	}
	
	public boolean checkAvailability() {
		return availableSeats > 0;
	}
	
	public void decreaseSeats() {
		if (availableSeats > 0) {
			availableSeats--;
		}
	}
	
	public void increaseSeats() {
		availableSeats++;
	}
	
	public void displayFlight() {
		System.out.println("Flight Number: " + flightNumber + ", Departure: " + departure + ", Destination: " + destination + ", Available Seats: " + availableSeats);
	}

}
