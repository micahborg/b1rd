# B1RD: Online Bird Adoption Platform Concept
***This project runs on the XAMPP Apache distribution***

## Instructions for Site Replication

1. Clone the Repository
2. Start/Run MySQL Server
3. Import objects/b1rd_db.SQL dump file into a databased named "b1rd_db"
4. Configure connection.php
5. Start/Run Apache Server

## Introduction

Our scenario revolves around developing an online bird adoption platform dedicated to
connecting bird lovers with avian rescue organizations and shelters offering birds for adoption.
The platform aims to streamline the process of finding, adopting, and caring for pet birds in
need of a new home. This approach to bird adoption allows for rescue organizations and shelters
to bird adoptions.

## Demo Video
https://github.com/user-attachments/assets/8479e7a8-fb5e-46d8-854c-2fbed97d0fcb

## Requirements Analysis

- ***Data***: The database will store comprehensive information about birds available for
adoption, including species, type, age, medical history, adoption status, and behavioral
traits. Additionally, user data such as contact information, adoption preferences, and
adoption history will be managed.
- ***Constraints***: The system should prioritize the security and privacy of user data, ensure
data accuracy and consistency, and enforce constraints to maintain the integrity of the
adoption process. Adoption requests should be processed efficiently, and only authorized
users should have access to sensitive information.
- ***Operations***: Users should be able to search for available birds based on criteria such as
species, age, location, and temperament. They should also be able to submit adoption
applications, track application status, and communicate with shelters and rescue
organizations.

## Conceptual Design

### ***Entity-Relationship (ER) Diagram***

- Entities:
    - Bird (BirdID, ShelterID, Species, Type, Age, MedicalHistory, AdoptionStatus,
    Behavior)
    - User (UserID, Name,PhoneNumber, Email, Password, UserType)
    - Shelter(ShelterID, PhoneNumber, Name, Location)
    - AdoptionApplication (ApplicationID, BirdID, UserID, ApplicationDate,
    AdoptionStatus)
- Relationships:
    - Shelter HAS Bird (HAS contains the attribute IsRescused)
    - User ADOPTS Bird
    - User FILES AdoptionApplication
    - AdoptionApplication HAS one Bird
    - Bird HAS one Shelter
    - Bird-AdoptionApplication: One-to-Many
    - User-AdoptionApplication: One-to-Many
    - Bird-Shelter: One-to-Many
- Constraints:
    - Each bird must be associated with a shelter.
    - Each adoption application must be linked to a specific bird and user.
    - Adoption status should be well-defined and include values such as Available, Pending,
    and Adopted.
    

### ***Logical Design***

- Relational Schemas:
    - Bird (BirdID, ShelterID, Species, Type, Age, MedicalHistory, AdoptionStatus,
    Behavior)
    - User (UserID, Name, PhoneNumber, Email, Password, UserType)
    - Shelter(ShelterID, PhoneNumber, Name, Location)
    - AdoptionApplication (ApplicationID, BirdID, UserID, ApplicationDate,
    AdoptionStatus)
- Normalization:
    - The schemas adhere to the principles of normalization to ensure data consistency and
    eliminate redundancy.
    - Foreign key constraints are established to maintain referential integrity between
    related tables.
    
    This tailored bird adoption platform will cater specifically to bird enthusiasts, providing a
    user-friendly interface to search for, apply to adopt and care for pet birds. As we progress,
    system architecture, interface design, and implementation using PHP and MySQL will be our
    focus areas.
