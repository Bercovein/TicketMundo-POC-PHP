 drop database FinalProyect;
  create database FinalProyect;

use FinalProyect


create table Categories(

	category_id int auto_increment,
	category_name varchar(30) UNIQUE not null,
	category_state int default 0,
	primary key(category_id)
);

insert into Categories (category_name) 
	values ("Obra Teatral"),("Concierto"),("Festival"),
	("Excibicion"),("Exposicion"), ("Convencion");

create table Artists(

	artist_id int auto_increment,
	artist_name varchar(30) UNIQUE not null,
	artist_state int default 0,
	primary key(artist_id)
);

insert into Artists (artist_name) 
	values ("Metallica"),("Helloween"),("Arch Enemy"),("Megadeth"),("Iron Maiden"),
	("Stan Lee"),("Harakiri"),("Robert Downey Jr"),("Kit Harington"),("Peter Dinklage"),
	("Steve Vai"),("Ian McKellen"),("Elijah Wood"),("Billy Boyd"),("Emilia Clarke"),
	("Cirque Du Soleil"),("Conxuro"),("Batmetal"),("Dethklok"),("Juan Azar"),
	("Seba Soler"),("Fernando Castaneda"),("Sonata Arctica"),("Scorpions"),("Dave Evans"), 
	("Kreator");

create table EventPlaces(

	place_id int auto_increment,
	capacity int not null,
	place_name varchar(30) UNIQUE not null,
	place_state int default 0,
	primary key(place_id)
);

insert into EventPlaces (place_name,capacity) 
	values ("Vorterix","5000"),("Abbey Road","3000"),("Estadio Velez","200000"),
		("Gran Rex","4000"),("Mvseo","2000"),("Vinoteca","250"),
		("Luna Park","10000"),("Maipo","6000"),("Estadio River","250000"),
		("Centro Costa Salguero","16000"),("Teatro Colon","10000"),("Gier Music Club","22000");


create table TypeOfSeats(

	type_id int auto_increment,
	type_name varchar(30) UNIQUE not null,
	type_state int default 0,
	primary key(type_id)
);

insert into TypeOfSeats (type_name) 
	values ("Platea"),("Campo"),("Campo Vip"),("Pullman"),("General"), ("SuperPullman");

create table Events(

	event_id int auto_increment,
	fk_category int not null,
	title varchar(50) UNIQUE not null,
	banner varchar(50) default null,
	event_state int default 0,
	primary key(event_id),
	foreign key (fk_category) references Categories (category_id)
);

insert into Events (title, fk_category, banner) 
	values ("Helloween Pumpkins United World Tour","3","Artista_2019_Helloween.jpg"),
	("Argentina Comic Con","6","comiccon-show.jpg"),
	("Iron Maiden Legacy of the Beast Tour","2","iron-maiden-argentina-2019.jpg"),
	("Sonata Arctica The Ninth Tour","2","sonata_cover.jpg"),
	("Dave Evans in Concert","2","dave-evans-show.jpg"),
	("Batmetal Forever","2","Batmetal.jpg"), 
	("Lord of The Rings El Musical","1","el-senor-de-los-anillos.jpg"),
	("Game of Thrones ExpoSet","5","Ned-Stark-Game-of-Thrones.jpg"),
	("Presentacion Proyecto Final","5","UTNFIN.jpg"),
	("Dethklok el Musical","1","dethklok.jpg");

create table Calendars(

	calendar_id int auto_increment,
	day DateTime not null,
	fk_event int not null,
	fk_eventplace int not null,
	calendar_state int default 0,
	primary key(calendar_id),
	foreign key (fk_event) references Events (event_id),
	foreign key (fk_eventplace) references EventPlaces (place_id)
);


insert into Calendars (day,fk_event,fk_eventplace) 
	values ("2020/12/05 18:30:00",4,1), ("2020/11/22 20:30:00",9,2),
			("2020/10/12 20:30:00",3,3),("2020/12/25 22:00:00",6,9),
			("2020/12/07 21:15:00",2,10),("2020/12/08 19:00:00",2,10),
			("2020/12/09 15:30:00",2,10),("2020/11/24 12:00:00",5,12),
			("2020/12/31 20:30:00",10,6),("2020/01/02 19:00:00",7,11),
			("2020/11/08 19:00:00",1,7),("2020/05/12 21:15:00",8,11),
			("2019/06/17 18:30:00",8,8);

create table EventSeats(

	seats_id int auto_increment,
	quantity int not null,
	price float not null,
	remanents int not null,
	pfk_typeofseats int not null,
	pfk_calendar int not null,
	primary key(seats_id, pfk_calendar, pfk_typeofseats),
	foreign key (pfk_typeofseats) references TypeOfSeats (type_id),
	foreign key (pfk_calendar) references Calendars (calendar_id)
);

insert into EventSeats(pfk_calendar, pfk_typeofseats, quantity, remanents, price)
	values(1,2,2500,2500,1500), (1,1,2500,2500,2000),
		(2,5,3000,3000,150),
		(3,1,50000,50000,2500),(3,2,100000,100000,2000),(3,3,50000,50000,1700),
		(4,1,50000,50000,3000), (4,2,50000,50000,2500), (4,4,50000,50000,2200), (4,3,50000,50000,4000),
		(5,5,16000,16000,2000), (6,5,16000,16000,2000), (7,5,16000,16000,2000),
		(8,2,10000,10000,1600), (8,4,6000,6000,1800), (8,6,5000,5000,2000),
		(9,1,250,250,900),
		(10,2,7500,7500,1000), (10,3,2500,2500,1500),
		(11,2,7500,7500,1700), (11,1,2500,2500,2100),
		(12,5,10000,10000,3000),
		(13,5,6000,6000,3000);

create table CalendarsXArtists(

	pfk_ca_calendar int not null,
	pfk_artist int not null,
	primary key (pfk_ca_calendar,pfk_artist),
	foreign key (pfk_ca_calendar) references Calendars (calendar_id),
	foreign key (pfk_artist) references Artists (artist_id)
);

insert into CalendarsXArtists(pfk_ca_calendar, pfk_artist)
	values (1,23),(1,1),(1,4),
		(2,20),(2,21),(2,22),
		(3,5),(3,24),
		(4,18),(4,19),
		(5,6),(5,7),
		(6,6),(6,7),
		(7,6),(7,8),
		(8,25),
		(9,19),(9,17),
		(10,12),(10,13),(10,14),
		(11,2),(11,3),(11,26),
		(12,9),(12,10),(12,15),
		(13,9),(13,10),(13,15);

create table Users(

	user_id int auto_increment,
	user_email varchar(30) unique not null,
	password varchar(30) not null,
	rol char not null default "C",
	primary key(user_id)
	);

insert into Users (user_email,password, rol)
	values("bercovsky@admin","admin","A"), ("bertolotti@admin","admin","A"), 
	("lachiqui@client","lachiqui","C"), ("admin@admin","admin","A"),
	("juan@azar","juan","C"),("seba@soler","seba","C"),
	("fernando@castaneda","fer","C"), ("bercovein@gmail.com","fusah222","C");

create table Clients(

	client_id int auto_increment, 
	firstName varchar(30) not null,
	lastName varchar(30) not null,
	dni int unique not null,
	fk_user int not null,
	primary key(client_id),
	foreign key(fk_user) references Users(user_id)
);

insert into Clients (firstName, lastName, dni,fk_user) 
	values ("Mirtha","Legrand",1,3),("Juan","Azar", 100,5),
	("Sebastian","Soler", 101,6),("Fernando","Castaneda", 102,7),
	("Nicolas","Bercovsky",37893932,8);


create table Cards(

	card_id int auto_increment,
	card_number varchar(20) not null unique,
	securityCode int not null,
	expirationDate date not null,
	fk_client int not null,
	primary key (card_id),
	constraint cards_ibfk_1 foreign key(fk_client) references Clients (client_id)
);

insert into Cards (card_number, securityCode, expirationDate, fk_client) 
	values ("123456789","789", "2019/11/16",1),("987654321","321", "2019/10/16",2),
	("456789123","123", "2019/09/16",3),("123789456","456", "2019/06/16",4),
	("123456987","987", "2019/05/16",1), ("123789654","654", "2019/04/16",3), 
	("3789393222","222", "2019/12/16",5);


create table Purchases(

	purchase_id int auto_increment,
	purchase_date date not null,
	total int default 0,
	fk_client int not null,
	primary key (purchase_id),
	foreign key (fk_client) references Clients (client_id)
);

create table PurchaseLines(

	line_id int auto_increment,
	line_quantity int not null,
	line_price int not null,
	fk_eventseat int not null,
	fk_purchase int,
	primary key (line_id),
	foreign key (fk_eventseat) references EventSeats(seats_id),
	foreign key (fk_purchase) references Purchases(purchase_id)
);

create table Tickets(

	ticket_id int auto_increment,
	ticket_number int not null,
	fk_purchaseLine int not null,
	fk_client int not null,
	primary key(ticket_id),
	foreign key(fk_purchaseLine) references PurchaseLines (line_id),
	foreign key(fk_client) references Clients (client_id)
);


/*
DELETE FROM cli, car USING Clients AS cli INNER JOIN Cards AS car
    WHERE cli.client_id=car.fk_client 
    AND cli.dni LIKE "37893932";

*/
DELIMITER //
CREATE PROCEDURE FinalProyect.deleteClientCard(in _dni int)

	begin
		SET FOREIGN_KEY_CHECKS = 0;
		DELETE Clients, Cards FROM Clients, Cards  
		WHERE Cards.fk_client = Clients.client_id AND Clients.dni = _dni;
		SET FOREIGN_KEY_CHECKS = 1;
	end//

DELIMITER ;

