/* All Relevant Table creation code for the MySQL Database for this application */

CREATE TABLE IF NOT EXISTS Account_Types_Lookup (
	accountType VARCHAR(50) NOT NULL PRIMARY KEY,
        typeDescrition VARCHAR(50) NOT NULL      
        
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS Users_Lookup (
	userID INT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
        username VARCHAR(50) NOT NULL,
        userPassword VARCHAR(50) NOT NULL,
        accountType VARCHAR(50) NOT NULL,
        INDEX(`accountType`),
        FOREIGN KEY (accountType) REFERENCES Account_Types_Lookup(accountType)        
        
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS Stage_Key_Lookup (
        stageKey VARCHAR(2) NOT NULL PRIMARY KEY,
        keyDescription VARCHAR(50) NOT NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS Customer_Lookup (
	customerID INT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
        customerName VARCHAR(50) NOT NULL,
        customerAddress VARCHAR(150) NOT NULL,
        customerAddress2 VARCHAR(150) NOT NULL,
        customerCity VARCHAR(50) NOT NULL,
        customerState VARCHAR(2) NOT NULL,
        customerZipCode VARCHAR(10) NOT NULL,
        customerDeliveryAddress VARCHAR(150) NULL,
        customerDeliveryAddress2 VARCHAR(150) NULL,
        customerDeliveryCity VARCHAR(50) NULL,
        customerDeliveryState VARCHAR(2) NULL,
        customerDeliveryZipCode VARCHAR(10) NULL,
        customerEmail VARCHAR(50) NOT NULL,
        customerPhone VARCHAR(25) NOT NULL
        
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS Vessel_Lookup (
        vesselModel VARCHAR(50) NOT NULL PRIMARY KEY,
        vesselManufacturer VARCHAR(50) NOT NULL,
        vesselCapacity VARCHAR(10) NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS Customer_Vessels_Lookup (
        vesselID VARCHAR(50) NOT NULL PRIMARY KEY,
        customerID INT UNSIGNED NOT NULL,
        vesselName VARCHAR(50) NULL,
        vesselModel VARCHAR(50) NOT NULL,        
        lastInspection DATE NULL,
        nextInspection DATE NULL,
        imoNum VARCHAR(10) NULL,
        callSign VARCHAR(50) NULL,
        vesselFlag VARCHAR(50) NULL,
        dateOfMfr DATE NULL,
        classSociety VARCHAR(50) NULL,
        INDEX(`customerID`),
        INDEX(`vesselModel`),
        FOREIGN KEY (customerID) REFERENCES Customer_Lookup(customerID),
        FOREIGN KEY (vesselModel) REFERENCES Vessel_Lookup(vesselModel)
        
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS Product_Lookup (
        productID VARCHAR(50) NOT NULL PRIMARY KEY,
        productName VARCHAR(50) NOT NULL,
        productSerialNum VARCHAR(25) NOT NULL,
        productDescription VARCHAR(150) NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS WorkOrder_Lookup (
        woNum BIGINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
        customerID INT UNSIGNED NOT NULL,
        vesselID VARCHAR(50) NOT NULL,
        stageKey VARCHAR(2) NOT NULL,
        woDateCreated DATE NOT NULL,
        woEstCompletion DATE NULL,
        terms VARCHAR(25) NULL,
        rep VARCHAR(2) NULL,
        writtenBy VARCHAR(2) NULL,
        poNum VARCHAR(15) NULL,
        pdfFilepath VARCHAR(250) NULL,
        complete BOOLEAN NOT NULL,
        INDEX(`customerID`),
        INDEX(`vesselID`),
        INDEX(`stageKey`),
        FOREIGN KEY (customerID) REFERENCES Customer_Lookup(customerID),
        FOREIGN KEY (vesselID) REFERENCES Customer_Vessels_Lookup(vesselID),
        FOREIGN KEY (stageKey) REFERENCES Stage_Key_Lookup(stageKey)
        
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS WO_Items_Table (
        woNum BIGINT UNSIGNED NOT NULL,
        productID VARCHAR(50) NOT NULL,
        quantity INT UNSIGNED NOT NULL,
        INDEX(`woNum`),
        INDEX(`productID`),
        FOREIGN KEY (woNum) REFERENCES WorkOrder_Lookup(woNum),
        FOREIGN KEY (productID) REFERENCES Product_Lookup(productID),
        PRIMARY KEY (woNum, productID)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS ChangeLog_Table (
        userID INT UNSIGNED NOT NULL,
        changeDateTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        woNum BIGINT UNSIGNED NOT NULL,
        changeDescription VARCHAR(50) NULL,
        INDEX(`userID`),
        INDEX(`woNum`),
        FOREIGN KEY (woNum) REFERENCES WorkOrder_Lookup(woNum),
        FOREIGN KEY (userID) REFERENCES Users_Lookup(userID),
        PRIMARY KEY (woNum, changeDateTime)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS QC_Checklist_Table (
	checklistID VARCHAR(15)  NOT NULL PRIMARY KEY,
        woNum BIGINT UNSIGNED NOT NULL,
        item1 INT NULL,
        item2 INT UNSIGNED NULL,
        item3 INT UNSIGNED NULL,
        item4 INT UNSIGNED NULL,
        item5 INT UNSIGNED NULL,
        item5A VARCHAR(10) NULL,
        item5B VARCHAR(10) NULL,
        item6 INT UNSIGNED NULL,
        item6A DATE NULL,
        item6B VARCHAR(10) NULL,
        item7 INT UNSIGNED NULL,
        item7A DATE NULL,
        item7B DATE NULL,
        item7C DATE NULL,
        item8 INT UNSIGNED NULL,
        item8A VARCHAR(10) NULL,
        item8B VARCHAR(10) NULL,
        item8C DATE NULL,
        item9 INT UNSIGNED NULL,
        item10 INT UNSIGNED NULL,
        item10A VARCHAR(10) NULL,
        item11 INT UNSIGNED NULL,
        item12 INT UNSIGNED NULL,
        item13 INT UNSIGNED NULL,
        item14 INT UNSIGNED NULL,
        item15 INT UNSIGNED NULL,
        item16 INT UNSIGNED NULL,
        userID_Sig1 INT UNSIGNED NULL,
        userID_Sig2 INT UNSIGNED NULL,
        userID_Sig1_Filepath VARCHAR(250) NULL,
        userID_Sig2_Filepath VARCHAR(250) NULL,
        INDEX(`woNum`),
        FOREIGN KEY (woNum) REFERENCES WorkOrder_Lookup(woNum)
        
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS Inspection_Worksheet_Table (
        worksheetID INT UNSIGNED NOT NULL PRIMARY KEY,
        sheetID VARCHAR(15) NOT NULL,
        serviceDate DATE NULL,
        appovalNum VARCHAR(25) NULL,
        URPSI VARCHAR(25) NULL,
        URReliefOpen VARCHAR(25) NULL,
        URReliefReseat VARCHAR(25) NULL,
        URTimeOn VARCHAR(25) NULL,
        URTimeOff VARCHAR(25) NULL,
        URPressureOn VARCHAR(25) NULL,
        URPressureOff VARCHAR(25) NULL,
        URTemperatureOn VARCHAR(25) NULL,
        URTemperatureOff VARCHAR(25) NULL,
        URBarometerOn VARCHAR(25) NULL,
        URBarometerOff VARCHAR(25) NULL,
        URFinishPressure VARCHAR(25) NULL,
        URCorrectedPressure VARCHAR(25) NULL,
        URPassFail VARCHAR(25) NULL,
        LRPSI VARCHAR(25) NULL,
        LRReliefOpen VARCHAR(25) NULL,
        LRReliefReseat VARCHAR(25) NULL,
        LRTimeOn VARCHAR(25) NULL,
        LRTimeOff VARCHAR(25) NULL,
        LRPressureOn VARCHAR(25) NULL,
        LRPressureOff VARCHAR(25) NULL,
        LRTemperatureOn VARCHAR(25) NULL,
        LRTemperatureOff VARCHAR(25) NULL,
        LRBarometerOn VARCHAR(25) NULL,
        LRBarometerOff VARCHAR(25) NULL,
        LRFinishPressure VARCHAR(25) NULL,
        LRCorrectedPressure VARCHAR(25) NULL,
        LRPassFail VARCHAR(25) NULL,
        FLPSI VARCHAR(25) NULL,
        FLReliefOpen VARCHAR(25) NULL,
        FLReliefReseat VARCHAR(25) NULL,
        FLTimeOn VARCHAR(25) NULL,
        FLTimeOff VARCHAR(25) NULL,
        FLPressureOn VARCHAR(25) NULL,
        FLPressureOff VARCHAR(25) NULL,
        FLTemperatureOn VARCHAR(25) NULL,
        FLTemperatureOff VARCHAR(25) NULL,
        FLBarometerOn VARCHAR(25) NULL,
        FLBarometerOff VARCHAR(25) NULL,
        FLFinishPressure VARCHAR(25) NULL,
        FLCorrectedPressure VARCHAR(25) NULL,
        FLPassFail VARCHAR(25) NULL,
        FiveYearInflation VARCHAR(25) NULL,
        FloorStrength VARCHAR(25) NULL,
        NAP VARCHAR(25) NULL, 
        ReleaseHook VARCHAR(25) NULL,
        LoadTest VARCHAR(25) NULL,
        CylinderSerialA VARCHAR(25) NULL,
        CylinderSerialB VARCHAR(25) NULL,
        WeightCO2A VARCHAR(25) NULL,
        WeightCO2B VARCHAR(25) NULL,
        WeightN2A VARCHAR(25) NULL,
        WeightN2B VARCHAR(25) NULL,
        GrossWeightA VARCHAR(25) NULL,
        GrossWeightB VARCHAR(25) NULL,
        HydroTestDueDateA VARCHAR(25) NULL,
        HydroTestDueDateB VARCHAR(25) NULL,
        Comments VARCHAR(500) NULL
        
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS Component_Table (
        sheetID VARCHAR(15) NOT NULL PRIMARY KEY,
        woNum BIGINT UNSIGNED NOT NULL,
        boardingLadder VARCHAR(25) NULL,
        boardingRamp VARCHAR(25) NULL,
        reflectiveTape VARCHAR(25) NULL,
        seaAnchor VARCHAR(25) NULL,
        innerOuterLifeLine VARCHAR(25) NULL,
        seaLightInner VARCHAR(25) NULL,
        seaLightOuter VARCHAR(25) NULL,
        floatingKnife VARCHAR(25) NULL,
        heavingLine VARCHAR(25) NULL,
        painterAssembly VARCHAR(25) NULL,
        rightingStraps VARCHAR(25) NULL,
        ballastBags VARCHAR(25) NULL,
        doubleFloor VARCHAR(25) NULL,
        canopy VARCHAR(25) NULL,
        rainwaterCollector VARCHAR(25) NULL,
        canopySupportTube VARCHAR(25) NULL,
        cylinderPouch VARCHAR(25) NULL,
        cylinderHeadCover VARCHAR(25) NULL,
        inflationCylinder VARCHAR(25) NULL,
        cylinderValve VARCHAR(25) NULL,
        cvSerialNum VARCHAR(25) NULL,
        cylinderPullCable VARCHAR(25) NULL,
        cylinderValveAdapter VARCHAR(25) NULL,
        cylinderHydroTest VARCHAR(25) NULL,
        cylinderRefill VARCHAR(25) NULL,
        inflationHose VARCHAR(25) NULL,
        inletValvePoppetAssembly VARCHAR(25) NULL,
        toppingUpValves VARCHAR(25) NULL,
        prvValve VARCHAR(25) NULL,
        prvPlugs VARCHAR(25) NULL,
        valise VARCHAR(25) NULL,
        vacuumBag VARCHAR(25) NULL,
        container VARCHAR(25) NULL,
        valiseIDPlacard VARCHAR(25) NULL,
        valiseLabels VARCHAR(25) NULL,
        containerGasket VARCHAR(25) NULL,
        containerSealTape VARCHAR(25) NULL,
        painterPlug VARCHAR(25) NULL,
        containerBurstingStrap VARCHAR(25) NULL,
        solasID VARCHAR(25) NULL,
        cradle VARCHAR(25) NULL,
        firingHead VARCHAR(25) NULL,
        firingHeadSerialNum VARCHAR(25) NULL,
        userID_Sig1 INT UNSIGNED NULL,
        userID_Sig2 INT UNSIGNED NULL,
        userID_Sig1_Filepath VARCHAR(250) NULL,
        userID_Sig2_Filepath VARCHAR(250) NULL,
        INDEX(`woNum`),
        FOREIGN KEY (woNum) REFERENCES WorkOrder_Lookup(woNum)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS Survival_Table (
        sheetID VARCHAR(15) NOT NULL PRIMARY KEY,
        woNum BIGINT UNSIGNED NOT NULL,
        equipmentBag VARCHAR(25) NULL,
        handPumpHoseAdapter VARCHAR(25) NULL,
        sealingPlugs VARCHAR(25) NULL,
        spareSeaAnchor VARCHAR(25) NULL,
        instructions VARCHAR(25) NULL,
        paddles VARCHAR(25) NULL,
        sponges VARCHAR(25) NULL,
        canOpener VARCHAR(25) NULL,
        signalWhistles VARCHAR(25) NULL,
        signalMirror VARCHAR(25) NULL,
        fishingKit VARCHAR(25) NULL,
        flashlight VARCHAR(25) NULL,
        spareBulb VARCHAR(25) NULL,
        drinkingCup VARCHAR(25) NULL,
        jackKnife VARCHAR(25) NULL,
        seaSickBags VARCHAR(25) NULL,
        thermalProtectiveAids VARCHAR(25) NULL,
        firstAidKit VARCHAR(25) NULL,
        repairKit VARCHAR(25) NULL,
        desalinator VARCHAR(25) NULL,
        bailer VARCHAR(25) NULL,
        epirb VARCHAR(25) NULL,
        INDEX(`woNum`),
        FOREIGN KEY (woNum) REFERENCES WorkOrder_Lookup(woNum)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS Dated_Items_Table (
        sheetID VARCHAR(15) NOT NULL PRIMARY KEY,
        woNum BIGINT UNSIGNED NOT NULL,
        rations VARCHAR(50) NULL,
        water VARCHAR(50) NULL,
        burnCream VARCHAR(50) NULL,
        aspirin VARCHAR(50) NULL,
        iodineSwabs VARCHAR(50) NULL,
        eyeWash VARCHAR(50) NULL,
        seasickPills VARCHAR(50) NULL,
        handFlares VARCHAR(50) NULL,
        parachuteFlares VARCHAR(50) NULL,
        chooseSmoke VARCHAR(50) NULL,
        dCellBatteries VARCHAR(50) NULL,
        repairKitCement VARCHAR(50) NULL,
        seaLightCells VARCHAR(50) NULL,
        hydrostaticRelease VARCHAR(50) NULL,
        epirbBattery VARCHAR(50) NULL,
        INDEX(`woNum`),
        FOREIGN KEY (woNum) REFERENCES WorkOrder_Lookup(woNum)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

INSERT INTO `account_types_lookup` (`accountType`, `typeDescrition`) VALUES ('Admin', 'Access to everything.');

INSERT INTO `users_lookup` (`userID`, `username`, `userPassword`, `accountType`) VALUES ('1', 'admin', '692B0F5C02CE18FD0E9750C1122D40E84373AB3B', 'Admin');

INSERT INTO `product_lookup` (`productID`, `productName`, `productSerialNum`, `productDescription`) VALUES ('1', 'Inspection', '1234', 'Inspection of a liferaft.');

INSERT INTO `stage_key_lookup` (`stageKey`, `keyDescription`) VALUES ('0', 'To be picked up');

INSERT INTO `account_types_lookup` (`accountType`, `typeDescrition`) VALUES ('Technician', 'Allows users to edit work order details');

INSERT INTO `account_types_lookup` (`accountType`, `typeDescrition`) VALUES ('Receptionist', 'Allows users to create work orders, customer, and vessels');

INSERT INTO `account_types_lookup` (`accountType`, `typeDescrition`) VALUES ('Manager', 'User can edit final inspection worksheet');

INSERT INTO `account_types_lookup` (`accountType`, `typeDescrition`) VALUES ('Customer', 'Allows users to view their work orders.');

INSERT INTO `users_lookup` (`userID`, `username`, `userPassword`, `accountType`) VALUES ('2', 'receptionist', '692B0F5C02CE18FD0E9750C1122D40E84373AB3B', 'Receptionist');