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

CREATE TABLE User_preferences (
    userpref_ID INT PRIMARY KEY AUTO_INCREMENT,
    userpref_UserID INT NOT NULL,
    max_price DECIMAL(10, 2),
    min_price DECIMAL(10, 2),
    only_available BOOLEAN,
    FOREIGN KEY (userpref_UserID) REFERENCES Users(userID)
);

-- Create Userpref_brands junction table
CREATE TABLE Userpref_brands (
    userPrefID INT,
    Upref_brands VARCHAR(100),
    PRIMARY KEY (userPrefID, Upref_brands),
    FOREIGN KEY (userPrefID) REFERENCES User_preferences(userpref_ID)
);

-- Create Userpref_cat junction table
CREATE TABLE Userpref_cat (
    userPrefID INT,
    Upref_categ INT,
    PRIMARY KEY (userPrefID, Upref_categ),
    FOREIGN KEY (userPrefID) REFERENCES User_preferences(userpref_ID)
);


-- Create Userpref_stores junction table
CREATE TABLE Userpref_stores (
    userPrefID INT,
    Upref_stores INT,
    PRIMARY KEY (userPrefID, Upref_stores),
    FOREIGN KEY (userPrefID) REFERENCES User_preferences(userprefID)
);

-- create Shoes
CREATE TABLE Shoes (
    shoeID INT PRIMARY KEY AUTO_INCREMENT,
    categoryID INT,
    name VARCHAR(100) NOT NULL,
    brandID INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    releaseDate DATE NOT NULL,
    description TEXT NOT NULL,
    material TEXT NOT NULL,
    gender ENUM('Male','Female','Prefer not to say'),
    image_url VARCHAR(255) NOT NULL,
    size_range VARCHAR(50), -- idk if this works or if it would be more normalized if we made a seperate table then derive this from there?
    colour VARCHAR(100) NOT NULL,
    Upref_stores INT,
    FOREIGN KEY (brandID) REFERENCES Brands(brandID)
);

-- User_Shoes
CREATE TABLE User_Shoes (
	h_userID INT,
    h_shoeID INT,
    PRIMARY KEY(h_userID, h_shoeID),
    FOREIGN KEY(h_userID) REFERENCES Users(userID),
    FOREIGN KEY(h_shoeID) REFERENCES Shoes(shoeID)
);

-- Create Stores table
CREATE TABLE Stores (
    storeID INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    shoeID VARCHAR(50) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL
);
