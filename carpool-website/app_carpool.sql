drop table if exists users;
create table users
(
   email varchar(100) not null,
   password varchar(100) not null,
   first_name varchar(100) null,
   last_name varchar(100) null,
   bday date null,
   drivers_license_number varchar(40),
   connected_facebook_email varchar(100) not null,
   is_facebook_user varchar(1) not null,
   create_date datetime not null,
   update_date datetime not null,
   primary key
   (
      email
   )
);
insert into users values ('fernie255@yahoo.com','password','Fernando','Fajardo','2000-01-01','7ABCDEF','fernie255@yahoo.com','N',now(),now());
insert into users values ('user1@gmail.com','password','first1','last1','1996-01-01','5ABC001','user1@gmail.com','N',now(),now());
insert into users values ('user2@gmail.com','password','first2','last2','1996-01-01','5ABC002','user2@gmail.com','N',now(),now());

drop table if exists routes;
create table routes
(
   route_id int not null auto_increment,
   email varchar(25) not null,
   start_address varchar(200) not null,
   start_lat double not null,
   start_lng double not null,
   start_google_place_id varchar(200) not null,
   end_address varchar(200) not null,
   end_lat double not null,
   end_lng double not null,
   end_google_place_id varchar(200) not null,
   time_window_start datetime not null,
   time_window_end datetime not null,
   create_date datetime not null,
   status varchar(25) not null,
   type varchar(10) not null,
   primary key
   (
      route_id
   )
);
insert into routes values (1,'fernie255@yahoo.com','California State University Long Beach, Long Beach, CA, United States',33.783782,-118.114082,'ChIJQ_aCKdgx3YARqy3HziZ_3B8','STAPLES Center, South Figueroa Street, Los Angeles, CA',34.042950,-118.267123,'ChIJWXNsX7jHwoARaduMfEQ0HuU','2016-10-07 10:00:00','2016-10-07 12:00:00',now(), 'VISIBLE','REQUEST');
insert into routes values (2,'fernie255@yahoo.com','California State University Long Beach, Long Beach, CA, United States',33.783782,-118.114082,'ChIJQ_aCKdgx3YARqy3HziZ_3B8','STAPLES Center, South Figueroa Street, Los Angeles, CA',34.042950,-118.267123,'ChIJWXNsX7jHwoARaduMfEQ0HuU','2016-10-07 10:00:00','2016-10-07 12:00:00',now(), 'VISIBLE','REQUEST');
insert into routes values (3,'user1@gmail.com','California State University Long Beach, Long Beach, CA, United States',33.783782,-118.114082,'ChIJQ_aCKdgx3YARqy3HziZ_3B8','STAPLES Center, South Figueroa Street, Los Angeles, CA',34.042950,-118.267123,'ChIJWXNsX7jHwoARaduMfEQ0HuU','2016-10-07 10:00:00','2016-10-07 12:00:00',now(), 'VISIBLE','REQUEST');
insert into routes values (4,'user1@gmail.com','California State University Long Beach, Long Beach, CA, United States',33.783782,-118.114082,'ChIJQ_aCKdgx3YARqy3HziZ_3B8','STAPLES Center, South Figueroa Street, Los Angeles, CA',34.042950,-118.267123,'ChIJWXNsX7jHwoARaduMfEQ0HuU','2016-10-07 10:00:00','2016-10-07 12:00:00',now(), 'VISIBLE','OFFER');

drop table if exists messages;
create table messages
(
   message_id int not null auto_increment,
   route_id int not null,
  thread_name varchar(25) not null ,
   username varchar(25) not null,
  message_text varchar(1000) not null,
   primary key
   (
      message_id
   )
);
insert into messages values (1,1,'user1@gmail.com','user1@gmail.com','Hi.  I would like to join your ride.  Let me know');
insert into messages values (2,1,'user1@gmail.com','fernie255@yahoo.com','How flexible are you in he departure time?');
insert into messages values (3,1,'user1@gmail.com','user1@gmail.com','I can leave whenever you want to.');
insert into messages values (4,1,'user1@gmail.com','fernie255@yahoo.com','Ok.  I''ll let you know.');
insert into messages values (5,2,'user1@gmail.com','user1@gmail.com','Hi.  I would like to join your 2nd ride.  Let me know');
insert into messages values (6,2,'user1@gmail.com','fernie255@yahoo.com','How flexible are you in he 2nd departure time?');
insert into messages values (7,2,'user1@gmail.com','user1@gmail.com','I can leave whenever you want to go on 2nd ride.');
insert into messages values (8,2,'user1@gmail.com','fernie255@yahoo.com','Ok.  I''ll let you know about the 2nd ride.');
insert into messages values (9,2,'user1@gmail.com','fernie255@yahoo.com','I''m going to leave 1 hour later on the 2nd ride.');
insert into messages values (10,3,'fernie255@yahoo.com','fernie255@yahoo.com','How flexible are you in he 3nd departure time?');
insert into messages values (11,3,'fernie255@yahoo.com','user1@gmail.com','I can leave whenever you want to go on 3nd ride.');
insert into messages values (12,1,'user2@gmail.com','user2@gmail.com','Are you still doing this ride?');
insert into messages values (13,1,'user2@gmail.com','fernie255@yahon.com','Yes.');
insert into messages values (14,4,'user2@gmail.com','user2@gmail.com','Yes.');
insert into messages values (15,4,'fernie255@yahoo.com','fernie255@yahoo.com','Yes no.');


drop table if exists passenger_list;
create table passenger_list
(
   route_id int not null,
   username varchar(100) not null
);


drop table if exists ratings;
create table ratings
(
   route_id int not null,
   username_passenger varchar(100) not null,
   rating int not null,
   primary key
   (
      route_id,
      username_passenger
   )
);
drop table if exists reset_requests;
create table reset_requests
(
   email varchar(100) not null,
   code varchar(8) not null,
   create_date datetime not null default now(),
   update_date datetime not null default now(),
   primary key
   (
      email
   )
);

