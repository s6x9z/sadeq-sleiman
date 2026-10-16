# ✈️ Flight Reservation System

## Java Data Structures & Object-Oriented Programming Project

The **Flight Reservation System** is a Java-based console application developed for **CMPS347 at Beirut Arab University**.

The project applies core **Data Structures and Object-Oriented Programming concepts** to simulate a basic airline reservation system.

Users can:

- View available flights
- Create flight reservations
- Search for reservations
- Cancel existing reservations
- View all active reservations
- View cancelled reservations
- Track available seats

The system demonstrates the practical use of a **custom Linked List, Stack, Priority Queue, classes, objects, encapsulation, and object relationships** in Java.

---

# 📌 Project Overview

The purpose of the project was to design a simple flight reservation system while applying data structures to a realistic problem.

Instead of storing reservations using a basic array, the system uses a custom **Linked List** built using `Node` objects.

Additional data structures are used for different responsibilities:

```text
Linked List
    ↓
Active Reservations

Stack
    ↓
Cancelled Reservations

Priority Queue
    ↓
Reservation Priority Ordering
```

The application is operated through a command-line menu and allows the user to manage reservations interactively.

---

# ✨ Main Features

- ✈️ Display available flights
- 🎫 Create a reservation
- 🔎 Search for a reservation
- ❌ Cancel a reservation
- 📋 Display all active reservations
- 🗂️ Track cancelled reservations
- 💺 Manage available flight seats
- 🔗 Store reservations using a custom Linked List
- 📚 Store cancelled bookings using a Stack
- ⚡ Demonstrate Priority Queue ordering
- 🧩 Apply Object-Oriented Programming
- 💻 Interactive command-line interface

---

# 🎯 Project Objectives

The main objectives of the project were to:

- Apply Data Structures concepts using Java
- Develop a functional reservation-management application
- Implement a custom singly linked list
- Create and manage linked-list nodes
- Use a Stack for cancelled reservations
- Work with Java's Priority Queue
- Apply Object-Oriented Programming concepts
- Separate responsibilities into multiple classes
- Manage flight-seat availability
- Implement reservation insertion
- Implement reservation searching
- Implement reservation deletion
- Build an interactive menu-driven program

---

# 🏗️ System Architecture

The application is organized into multiple Java classes, each responsible for a different part of the reservation system.

```text
             FlightReservationSystem
                      │
        ┌─────────────┼─────────────┐
        │             │             │
        ▼             ▼             ▼
     Flight       Customer     Reservation
                                    │
                                    ▼
                           ReservationLinkedList
                                    │
                                    ▼
                                   Node

                    Additional Structures
                             │
                   ┌─────────┴─────────┐
                   ▼                   ▼
                 Stack           Priority Queue
```

This structure keeps the application modular and separates the responsibilities of flights, customers, reservations, and data management.

---

# ✈️ Flight Class

The `Flight` class represents an available flight.

Each flight contains:

```text
Flight Number
Departure Location
Destination
Available Seats
```

Example:

```java
Flight flight1 = new Flight(
    "MEA101",
    "BEIRUT",
    "KUWAIT CITY",
    5
);
```

A second flight is also included:

```java
Flight flight2 = new Flight(
    "TK102",
    "ISTANBUL",
    "BANGKOK",
    3
);
```

---

## Flight Responsibilities

The `Flight` class provides functionality for:

- Retrieving the flight number
- Checking whether seats are available
- Decreasing available seats after booking
- Increasing available seats after cancellation
- Displaying flight information

Example availability check:

```java
public boolean checkAvailability() {
    return availableSeats > 0;
}
```

---

# 💺 Seat Availability Management

Seat availability changes automatically as reservations are created or cancelled.

### When a reservation is created:

```text
Available Seats
      │
      ▼
Reservation Created
      │
      ▼
Available Seats - 1
```

The `Reservation` constructor calls:

```java
flight.decreaseSeats();
```

### When a reservation is cancelled:

```text
Reservation Cancelled
      │
      ▼
Reservation Removed
      │
      ▼
Available Seats + 1
```

The system restores the seat using:

```java
removed.getFlight().increaseSeats();
```

This keeps flight availability synchronized with the reservation system.

---

# 👤 Customer Class

The `Customer` class represents the person making a reservation.

Each customer contains:

```text
Customer ID
Name
Email Address
```

The constructor is:

```java
public Customer(
    String customerId,
    String name,
    String email
)
```

Customer information is linked to a reservation when a booking is created.

---

# 🎫 Reservation Class

The `Reservation` class connects:

```text
Customer
   +
Flight
   +
Reservation Number
```

into one reservation object.

Each reservation contains:

- Reservation number
- Flight
- Customer

Example:

```java
Reservation newReservation =
    new Reservation(
        reservationNumber,
        selectedFlight,
        customer
    );
```

Creating the reservation also decreases the available seats for the selected flight.

---

# 🔗 Custom Linked List

The main data structure used to manage active reservations is a **custom singly linked list**.

The project implements the linked list manually rather than using Java's built-in `LinkedList`.

```text
HEAD
 │
 ▼
Node
 │
 ▼
Reservation
 │
 ▼
Next
 │
 ▼
Node
 │
 ▼
Reservation
 │
 ▼
Next
 │
 ▼
...
```

The linked list is implemented in:

```text
ReservationLinkedList.java
```

---

# 🧩 Node Class

Each element of the linked list is represented by a `Node`.

A node contains:

```java
public Reservation reservation;
public Node next;
```

When created:

```java
public Node(Reservation reservation) {
    this.reservation = reservation;
    this.next = null;
}
```

The `next` reference connects one reservation node to the next node in the list.

---

# ➕ Reservation Insertion

New reservations are inserted at the end of the linked list.

The process is:

```text
Create Reservation
       │
       ▼
Create New Node
       │
       ▼
Is List Empty?
   │         │
  Yes        No
   │         │
   ▼         ▼
Set Head   Traverse List
             │
             ▼
          Find Last Node
             │
             ▼
         Link New Node
```

The insertion method is implemented as:

```java
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
```

---

# 🔎 Reservation Search

The linked list can be searched using a reservation number.

The algorithm starts at the head and traverses through each node.

```text
HEAD
 │
 ▼
Check Reservation Number
 │
 ├── Match → Found
 │
 └── No Match
        │
        ▼
     Next Node
        │
        ▼
       ...
```

Implementation:

```java
public boolean search(String reservationNumber) {
    Node current = head;

    while (current != null) {
        if (
            current.reservation
                .getReservationNumber()
                .equals(reservationNumber)
        ) {
            return true;
        }

        current = current.next;
    }

    return false;
}
```

---

# ❌ Reservation Deletion

Reservations can also be removed from the linked list.

The deletion algorithm handles:

- Empty lists
- Deleting the first node
- Deleting a node in the middle
- Deleting the final node
- Reservation numbers that do not exist

When a reservation is found, the links between nodes are updated so the reservation is removed from the chain.

```text
Before

A → B → C → D

Delete C

A → B ─────→ D
```

The deleted reservation is returned so it can also be stored in the cancelled-reservation Stack.

---

# 📚 Stack — Cancelled Reservations

A **Stack** is used to keep track of cancelled reservations.

```java
Stack<Reservation> canceledReservations =
    new Stack<>();
```

A Stack follows:

```text
LIFO

Last In
First Out
```

When a reservation is cancelled:

```java
canceledReservations.push(removed);
```

Conceptually:

```text
        STACK

       ┌────────┐
 TOP → │ Res 3  │  ← Most recently cancelled
       ├────────┤
       │ Res 2  │
       ├────────┤
       │ Res 1  │
       └────────┘
```

This demonstrates the use of Stack behavior for maintaining cancelled reservation history.

---

# ⚡ Priority Queue

The project also uses Java's:

```java
PriorityQueue<Reservation>
```

A custom comparator is defined:

```java
PriorityQueue<Reservation> priorityQueue =
    new PriorityQueue<>(
        (r1, r2) ->
            Integer.compare(
                r2.getReservationNumber().length(),
                r1.getReservationNumber().length()
            )
    );
```

Reservations are added using:

```java
priorityQueue.add(newReservation);
```

The comparator prioritizes reservations according to the **length of the reservation number**.

This component demonstrates how custom priority rules can be applied when storing objects in a Priority Queue.

> In the current implementation, reservations are added to the Priority Queue as a demonstration of priority ordering, while the interactive menu primarily manages reservations through the custom Linked List.

---

# 🔄 Reservation Creation Workflow

Creating a reservation follows this process:

```text
User Selects
Make Reservation
      │
      ▼
Enter Reservation Number
      │
      ▼
Display Flights
      │
      ▼
Select Flight
      │
      ▼
Check Seat Availability
      │
      ├── No Seats
      │      │
      │      ▼
      │   Stop Booking
      │
      └── Seat Available
             │
             ▼
       Enter Customer Info
             │
             ▼
        Create Customer
             │
             ▼
      Create Reservation
             │
             ▼
      Decrease Flight Seat
             │
             ▼
       Add to Linked List
             │
             ▼
      Add to Priority Queue
             │
             ▼
       Reservation Created
```

---

# ❌ Reservation Cancellation Workflow

Cancellation follows:

```text
Enter Reservation Number
          │
          ▼
Search Linked List
          │
      ┌───┴────┐
      │        │
   Found    Not Found
      │        │
      ▼        ▼
Delete      Display
Node        Error
      │
      ▼
Push Reservation
onto Stack
      │
      ▼
Restore Flight Seat
      │
      ▼
Cancellation Complete
```

This workflow combines multiple objects and data structures in one operation.

---

# 🖥️ Command-Line Interface

The main application uses a menu-driven interface.

```text
=== Flight Reservation System ===

1. Display Flights
2. Make a Reservation
3. Search Reservation
4. Cancel Reservation
5. Display All Reservations
6. Display Canceled Reservations
7. Exit

Choose an option:
```

The menu runs continuously until the user selects:

```text
7. Exit
```

---

# 📋 Display Flights

The first menu option displays the available flights and their current seat availability.

Example output:

```text
Flight Number: MEA101
Departure: BEIRUT
Destination: KUWAIT CITY
Available Seats: 5
```

The available-seat value changes when reservations are created or cancelled.

---

# 🔎 Search Reservation

Users can search for an existing booking by entering its reservation number.

```text
Enter Reservation Number to search:
ABC123
```

The Linked List is traversed to determine whether the reservation exists.

The system then displays either:

```text
Reservation found!
```

or:

```text
Reservation not found.
```

---

# 📋 Display All Reservations

The program can traverse the entire linked list and display every active reservation.

Each reservation includes:

- Reservation number
- Customer ID
- Customer name
- Customer email
- Flight number
- Departure
- Destination
- Available seats

---

# 🗂️ Display Cancelled Reservations

Cancelled reservations can be viewed through the Stack.

If no reservations have been cancelled:

```text
No canceled reservations.
```

Otherwise, the application displays the reservations stored in the Stack.

---

# 🧱 Object-Oriented Design

The project separates functionality across six Java classes:

```text
FlightReservationSystem.java
Flight.java
Customer.java
Reservation.java
ReservationLinkedList.java
Node.java
```

Each class has a specific responsibility.

---

## `FlightReservationSystem`

Main application controller.

Responsible for:

- Menu display
- User input
- Flight creation
- Reservation creation
- Reservation search
- Cancellation
- Displaying reservations
- Coordinating all classes and data structures

---

## `Flight`

Represents a flight.

Responsible for:

- Flight information
- Seat availability
- Seat increases
- Seat decreases

---

## `Customer`

Represents a customer.

Responsible for:

- Customer ID
- Customer name
- Customer email

---

## `Reservation`

Represents a booking.

Responsible for connecting:

```text
Customer + Flight + Reservation Number
```

---

## `ReservationLinkedList`

Custom Linked List implementation.

Responsible for:

- Insertion
- Searching
- Deletion
- Traversal
- Displaying reservations

---

## `Node`

Represents an individual linked-list node.

Contains:

- A `Reservation`
- A reference to the next `Node`

---

# 🛠️ Technologies Used

## ☕ Programming

- Java

## 🧩 Object-Oriented Programming

- Classes
- Objects
- Constructors
- Encapsulation
- Object relationships
- Modular class design

## 📚 Data Structures

- Custom Singly Linked List
- Nodes
- Stack
- Priority Queue

## 🔎 Algorithms & Operations

- Linked-list traversal
- Insertion
- Linear search
- Deletion
- Stack push
- Priority comparison

## 🔧 Development Environment

- Eclipse IDE
- Java Development Kit
- Command-line console

---

# 📂 Project Structure

```text
flight-reservation-system/
│
├── README.md
│
├── src/
│   ├── FlightReservationSystem.java
│   ├── Flight.java
│   ├── Customer.java
│   ├── Reservation.java
│   ├── ReservationLinkedList.java
│   └── Node.java
│
└── documentation/
    ├── Report.pdf
    └── Presentation.pptx
```

---

# 📁 Explore the Project

## 💻 Source Code

View the complete Java implementation.

[**View Source Code →**](./src/)

---

## 🚀 Main Application

View the main class containing the menu-driven reservation system.

[**View Main Application →**](./src/FlightReservationSystem.java)

---

## 🔗 Linked List Implementation

View the custom Linked List used to manage reservations.

[**View Linked List →**](./src/ReservationLinkedList.java)

---

## 🧩 Node Implementation

View the Node class used to build the custom Linked List.

[**View Node →**](./src/Node.java)

---

## ✈️ Flight Model

View the class responsible for flights and seat availability.

[**View Flight Class →**](./src/Flight.java)

---

## 🎫 Reservation Model

View the class connecting customers with their selected flights.

[**View Reservation Class →**](./src/Reservation.java)

---

## 👤 Customer Model

View the customer model.

[**View Customer Class →**](./src/Customer.java)

---

## 📄 Project Report

The project report explains the data structures, UML design, implementation, source code, and output.

[**View Report →**](./documentation/Report.pdf)

---

## 🎤 Project Presentation

View the project presentation.

[**View Presentation →**](./documentation/Presentation.pptx)

---

# 💡 What I Learned

This project provided practical experience applying **Data Structures and Object-Oriented Programming to a functional Java application**.

Through the project, I worked with:

- Java programming
- Object-Oriented Programming
- Class design
- Object relationships
- Custom Linked Lists
- Nodes
- Linked-list traversal
- Insertion algorithms
- Search algorithms
- Deletion algorithms
- Stacks
- Priority Queues
- Custom comparators
- Menu-driven applications
- User input handling
- Seat-availability management
- Modular application design
- Testing and debugging

A particularly valuable part of the project was understanding how different data structures can be used for different responsibilities within the same application.

For example:

```text
Linked List → Active reservations

Stack → Cancelled reservations

Priority Queue → Priority-based reservation ordering
```

---

# ⚠️ Project Scope

This project is an educational Java implementation designed to demonstrate **Data Structures and OOP concepts**.

It uses a command-line interface and predefined flights rather than a database or external airline service.

The system does not include:

- Online airline APIs
- Database persistence
- Payment processing
- User authentication
- Real airline schedules
- Graphical user interface

These features could be added in a larger production system.

---

# 🔮 Future Improvements

Possible improvements include:

- Building a graphical user interface using JavaFX
- Connecting the application to a database
- Persisting reservations between program sessions
- Allowing users to create accounts
- Adding administrator functionality
- Adding more flights dynamically
- Searching flights by destination
- Searching flights by departure city
- Adding departure and arrival times
- Adding ticket prices
- Adding seat-class selection
- Generating automatic reservation numbers
- Adding input validation
- Adding email validation
- Displaying complete reservation information during searches
- Expanding Priority Queue functionality
- Adding unit tests
- Implementing database-backed reservation history

---

# 🎓 Academic Context

**CMPS347 Project**  
**Beirut Arab University**

The Flight Reservation System was developed as a team academic project demonstrating practical use of **Data Structures and Object-Oriented Programming in Java**.

---

## 🔗 Project Navigation

[**💻 Source Code →**](./src/) · [**🚀 Main Application →**](./src/FlightReservationSystem.java) · [**🔗 Linked List →**](./src/ReservationLinkedList.java) · [**📄 Report →**](./documentation/Report.pdf) · [**🎤 Presentation →**](./documentation/Presentation.pptx)
