
public class Node {
	public Reservation reservation;
	public Node next;
	
	public Node (Reservation reservation) {
		this.reservation = reservation;
		this.next = null;
	}

}
