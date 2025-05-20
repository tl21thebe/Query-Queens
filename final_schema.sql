CREATE TABLE Users (
    userID INT PRIMARY KEY AUTO_INCREMENT,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    registrationDate DATE NOT NULL,
    name VARCHAR(100) NOT NULL,
    surname VARCHAR(100) NOT NULL,
    phoneNo VARCHAR(20) NOT NULL,
    country VARCHAR(100) NOT NULL,
    city VARCHAR(100) NOT NULL,
    street VARCHAR(100) NOT NULL
);

CREATE TABLE User_phoneNo (
    userID INT NOT NULL,
    U_phoneNo VARCHAR(20) NOT NULL,
    PRIMARY KEY (userID, U_phoneNo),
    FOREIGN KEY (userID) REFERENCES Users(userID)
);

