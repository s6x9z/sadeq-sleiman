
public class ReservationLinkedList {
	
	private Node head;
	
	public ReservationLinkedList() {
		head = null;
	}
	
	public void insert(Reservation reservation) {
		Node newNode = new Node(reservation);
		if (head == null) {
			head = newNode;
		} else {
			Node current = head;
			while (current.next != null) {
				current = current.next;
			}
			current.next = newNode;
		}
	}
	
	public boolean search(String reservationNumber) {
		Node current = head;
		while (current != null) {
			if (current.reservation.getReservationNumber().equals(reservationNumber)) {
				return true;
			}
			current = current.next;
		}
		return false;
	}
	
	public Reservation delete (String reservationNumber) {
		if (head == null) {
			return null;
		}
		if (head.reservation.getReservationNumber().equals(reservationNumber)) {
			Reservation deleted = head.reservation;
			head = head.next;
			return deleted;
		}
		Node current = head;
		while (current.next != null) {
			if (current.next.reservation.getReservationNumber().equals(reservationNumber)) {
				Reservation deleted = current.next.reservation;
				current.next = current.next.next;
				return deleted;
			}
			current = current.next;
		}
		return null;
	}
	
	public void display() {
		if (head == null) {
			System.out.println("No reservations found.");
			return;
		}
		Node current = head;
		while (current != null) {
			current.reservation.displayReservation();
			current = current.next;
		}
	}

}