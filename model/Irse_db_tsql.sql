CREATE TABLE account_types_lookup (
              accountType VARCHAR(50) NOT NULL PRIMARY KEY,
        typeDescrition VARCHAR(50) NOT NULL      
        
);

CREATE TABLE Users_Lookup (
              userID INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
        username VARCHAR(50) NOT NULL,
        userPassword VARCHAR(50) NOT NULL,
        accountType VARCHAR(50) NOT NULL,
        FOREIGN KEY (accountType) REFERENCES Account_Types_Lookup(accountType)        
        
);

CREATE TABLE Stage_Key_Lookup (
        stageKey VARCHAR(2) NOT NULL PRIMARY KEY,
        keyDescription VARCHAR(50) NOT NULL

);

CREATE TABLE Customer_Lookup (
              customerID INT IDENTITY(1,1) NOT NULL PRIMARY KEY,
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
        
);

CREATE TABLE Vessel_Lookup (
        vesselModel VARCHAR(50) NOT NULL PRIMARY KEY,
        vesselManufacturer VARCHAR(50) NOT NULL,
        vesselCapacity VARCHAR(10) NULL

)

CREATE TABLE Customer_Vessels_Lookup (
        vesselID VARCHAR(50) NOT NULL PRIMARY KEY,
        customerID INT NOT NULL,
        vesselName VARCHAR(50) NULL,
        vesselModel VARCHAR(50) NOT NULL,        
        lastInspection DATE NULL,
        nextInspection DATE NULL,
        imoNum VARCHAR(10) NULL,
        callSign VARCHAR(50) NULL,
        vesselFlag VARCHAR(50) NULL,
        dateOfMfr DATE NULL,
        classSociety VARCHAR(50) NULL,
        FOREIGN KEY (customerID) REFERENCES Customer_Lookup(customerID),
        FOREIGN KEY (vesselModel) REFERENCES Vessel_Lookup(vesselModel)
    
);

CREATE TABLE Product_Lookup (
        productID VARCHAR(50) NOT NULL PRIMARY KEY,
        productName VARCHAR(50) NOT NULL,
        productSerialNum VARCHAR(25) NOT NULL,
        productDescription VARCHAR(150) NULL

);

CREATE TABLE WorkOrder_Lookup (
        woNum BIGINT IDENTITY(1,1) NOT NULL PRIMARY KEY,
        customerID INT NOT NULL,
        vesselID VARCHAR(50) NOT NULL,
        stageKey VARCHAR(2) NOT NULL,
        woDateCreated DATE NOT NULL,
        woEstCompletion DATE NULL,
        terms VARCHAR(25) NULL,
        rep VARCHAR(2) NULL,
        writtenBy VARCHAR(2) NULL,
        poNum VARCHAR(15) NULL,
        pdfFilepath VARCHAR(250) NULL,
        complete BIT NOT NULL,
        FOREIGN KEY (customerID) REFERENCES Customer_Lookup(customerID),
        FOREIGN KEY (vesselID) REFERENCES Customer_Vessels_Lookup(vesselID),
        FOREIGN KEY (stageKey) REFERENCES Stage_Key_Lookup(stageKey)
        
);

CREATE TABLE WO_Items_Table (
        woNum BIGINT NOT NULL,
        productID VARCHAR(50) NOT NULL,
        quantity INT NOT NULL,
        FOREIGN KEY (woNum) REFERENCES WorkOrder_Lookup(woNum),
        FOREIGN KEY (productID) REFERENCES Product_Lookup(productID),
        PRIMARY KEY (woNum, productID)

);

CREATE TABLE QC_Checklist_Table (
              checklistID VARCHAR(15)  NOT NULL PRIMARY KEY,
        woNum BIGINT NOT NULL,
        item1 INT NULL,
        item2 INT NULL,
        item3 INT NULL,
        item4 INT NULL,
        item5 INT NULL,
        item5A VARCHAR(10) NULL,
        item5B VARCHAR(10) NULL,
        item6 INT NULL,
        item6A DATE NULL,
        item6B VARCHAR(10) NULL,
        item7 INT NULL,
        item7A DATE NULL,
        item7B DATE NULL,
        item7C DATE NULL,
        item8 INT NULL,
        item8A VARCHAR(10) NULL,
        item8B VARCHAR(10) NULL,
        item8C DATE NULL,
        item9 INT NULL,
        item10 INT NULL,
        item10A VARCHAR(10) NULL,
        item11 INT NULL,
        item12 INT NULL,
        item13 INT NULL,
        item14 INT NULL,
        item15 INT NULL,
        item16 INT NULL,
        userID_Sig1 INT NULL,
        userID_Sig2 INT NULL,
        userID_Sig1_Filepath VARCHAR(250) NULL,
        userID_Sig2_Filepath VARCHAR(250) NULL,
        FOREIGN KEY (woNum) REFERENCES WorkOrder_Lookup(woNum)
        
);

CREATE TABLE Inspection_Worksheet_Table (
        worksheetID INT NOT NULL PRIMARY KEY,
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
        
);

CREATE TABLE Component_Table (
        sheetID VARCHAR(15) NOT NULL PRIMARY KEY,
        woNum BIGINT NOT NULL,
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
        userID_Sig1 INT NULL,
        userID_Sig2 INT NULL,
        userID_Sig1_Filepath VARCHAR(250) NULL,
        userID_Sig2_Filepath VARCHAR(250) NULL,
        FOREIGN KEY (woNum) REFERENCES WorkOrder_Lookup(woNum)

);

CREATE TABLE Survival_Table (
        sheetID VARCHAR(15) NOT NULL PRIMARY KEY,
        woNum BIGINT NOT NULL,
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
        FOREIGN KEY (woNum) REFERENCES WorkOrder_Lookup(woNum)

);

CREATE TABLE Dated_Items_Table (
        sheetID VARCHAR(15) NOT NULL PRIMARY KEY,
        woNum BIGINT NOT NULL,
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
        FOREIGN KEY (woNum) REFERENCES WorkOrder_Lookup(woNum)

);
