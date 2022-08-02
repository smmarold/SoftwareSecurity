<?php 

include('db.php');

/***************************************************************************************************************************************************
 * 
 * Parts Checklist, also used on Inspection sheet, which has an identical Part List. Created a class that has every field in the list, so we 
 * dont have to pass anything from the page when calling the methods, as they will use the properties filled in that object 
 * 
 **************************************************************************************************************************************************/

Class PartsChecklist {
    //Component Vars. Also added 'name' vars with explicit item names. This is because we build the table on the page inside a foreach in the object, 
    //So the name properties are used to fill the label section of the table. 
    public $sheetID;    
    public $woNum;      
    public $boardingLadder; public $nameboardingLadder = "Boarding Ladder"; 
    public $boardingRamp; public $nameboardingRamp = "Boarding Ramp"; 
    public $reflectiveTape; public $namereflectiveTape = "Reflective Tape"; 
    public $seaAnchor; public $nameseaAnchor = "Sea Anchor"; 
    public $innerOuterLifeLine; public $nameinnerOuterLifeLine = "Inner/Outer Life Line"; 
    public $seaLightInner; public $nameseaLightInner =  "Sea Light Inner"; 
    public $seaLightOuter; public $nameseaLightOuter = "Sea Light Outer"; 
    public $floatingKnife; public $namefloatingKnife = "Floating Knife"; 
    public $heavingLine; public $nameheavingLine = "Heaving Line"; 
    public $painterAssembly; public $namepainterAssembly = "Painter Assembly"; 
    public $rightingStraps; public $namerightingStraps = "Righting Straps"; 
    public $ballastBags; public $nameballastBags = "Ballast Bags"; 
    public $doubleFloor; public $namedoubleFloor = "Double Floor"; 
    public $canopy; public $namecanopy = "Canopy"; 
    public $rainwaterCollector; public $namerainwaterCollector = "Rainwater Collector"; 
    public $canopySupportTube; public $namecanopySupportTube = "Canopy Support Tube"; 
    public $cylinderPouch; public $namecylinderPouch = "Cylinder Pouch"; 
    public $cylinderHeadCover; public $namecylinderHeadCover = "Cylinder Head Cover"; 
    public $inflationCylinder; public $nameinflationCylinder = "Inflation Cylinder"; 
    public $cylinderValve; public $namecylinderValve = "Cylinder Valve"; 
    public $cvSerialNum; public $namecvSerialNum = "Cylinder Valve Serial Number"; 
    public $cylinderPullCable; public $namecylinderPullCable = "Cylinder Pull Cable"; 
    public $cylinderValveAdapter; public $namecylinderValveAdapter = "Cylinder Valve Adapter"; 
    public $cylinderHydroTest; public $namecylinderHydroTest = "Cylinder Hydro Test"; 
    public $cylinderRefill; public $namecylinderRefill = "Cylinder Refill"; 
    public $inflationHose; public $nameinflationHose = "Inflation Hose"; 
    public $inletValvePoppetAssembly; public $nameinletValvePoppetAssembly = "Inlet Valve Poppet Assembly"; 
    public $toppingUpValves; public $nametoppingUpValves = "Topping Up Valves"; 
    public $prvValve; public $nameprvValve = "PRV Valve"; 
    public $prvPlugs; public $nameprvPlugs = "PRV Plugs"; 
    public $valise; public $namevalise = "Valise"; 
    public $vacuumBag; public $namevacuumBag = "Vacuum Bag"; 
    public $container; public $namecontainer = "Container"; 
    public $valiseIDPlacard; public $namevaliseIDPlacard = "Valise ID Placard"; 
    public $valiseLabels; public $namevaliseLabels = "Valise Labels"; 
    public $containerGasket; public $namecontainerGasket =  "Container Gasket"; 
    public $containerSealTape; public $namecontainerSealTape = "Container Seal Tape"; 
    public $painterPlug; public $namepainterPlug = "Painter Plug"; 
    public $containerBurstingStrap; public $namecontainerBurstingStrap = "Container Bursting Strap"; 
    public $solasID; public $namesolasID = "Solas ID"; 
    public $cradle; public $namecradle = "Cradle"; 
    public $firingHead; public $namefiringHead = "Firing Head"; 
    public $firingHeadSerialNum; public $namefiringHeadSerialNum = "Firing Head Serial Number"; 
    public $userID_Sig1;
    public $userID_Sig2;
    public $userID_Sig1_Filepath;
    public $userID_Sig2_Filepath;

    //Survival Vars
    public $equipmentBag; public $labelequipmentBag = "Equipment Bag";
    public $handPumpHoseAdapter; public $labelhandPumpHoseAdapter = "Hand Pump Hose Adapter";
    public $sealingPlugs; public $labelsealingPlugs = "Sealing Plugs";
    public $spareSeaAnchor; public $labelspareSeaAnchor = "Spare Sea Anchor";
    public $instructions; public $labelinstructions = "Instructions";
    public $paddles; public $labelpaddles = "Paddles";
    public $sponges; public $labelsponges = "Sponges";
    public $canOpener; public $labelcanOpener = "Can Opener";
    public $signalWhistles; public $labelsignalWhistles = "Signal Whistles";
    public $signalMirror; public $labelsignalMirror = "Signal Mirror";
    public $fishingKit; public $labelfishingKit = "Fishing Kit";
    public $flashlight; public $labelflashlight = "flashlight";
    public $spareBulb; public $labelspareBulb = "Spare Bulb";
    public $drinkingCup; public $labeldrinkingCup = "Drinking Cup";
    public $jackKnife; public $labeljackKnife = "Jack Knife";
    public $seaSickBags; public $labelseaSickBags = "Sea Sick Bags";
    public $thermalProtectiveAids; public $labelthermalProtectiveAids = "Thermal Protective Aids";
    public $firstAidKit; public $labelfirstAidKit = "First Aid Kit";
    public $repairKit; public $labelrepairKit = "Repair Kit";
    public $desalinator; public $labeldesalinator = "Desalinator";
    public $bailer; public $labelbailer = "Bailer";
    public $epirb; public $labelepirb = "Epirb";

    //Dated Items vars
    public $rations; public $datedrations = "Rations";
    public $water; public $datedwater = "Water";
    public $burnCream; public $datedburnCream = "Burn Cream";
    public $aspirin; public $datedaspirin = "Aspirin";
    public $iodineSwabs; public $datediodineSwabs = "Iodine Swabs";
    public $eyeWash; public $datedeyeWash = "Eye Wash";
    public $seasickPills; public $datedseasickPills = "Seasick Pills";
    public $handFlares; public $datedhandFlares = "Hand Flares";
    public $parachuteFlares; public $datedparachuteFlares = "Parachute Flares";
    public $chooseSmoke; public $datedchooseSmoke = "Choose Smoke";
    public $dCellBatteries; public $dateddCellBatteries = "D Cell Batteries";
    public $repairKitCement; public $datedrepairKitCement = "Repair Kit Cement";
    public $seaLightCells; public $datedseaLightCells = "Sea Light Cells";
    public $hydrostaticRelease; public $datedhydrostaticRelease = "Hydrostatic Release";
    public $epirbBattery; public $datedepirbBattery = "Epirb Battery (Optional)";

    //On save, each page will add a full table by calling this function, which then calls the other functions for adding each table (Each section of the parts list has it's own
    //Database Table as they store different amounts of information and require different methods of saving/filling)
    public function AddFullTable(){
        $results = "";
        $results .= $this->AddNewComponentsList();
        $results .= $this->AddNewSurvivalList();
        $results .= $this->AddNewDatedItemsList();

        return $results;
    }

    //Same as adding a new table, but for updating. 
    public function UpdateFullTable(){
        $results = "";
        $results .= $this->updateComponentsList();
        $results .= $this->UpdateSurvivalList();
        $results .= $this->UpdateDatedItemsTable();

        return $results;
    }
    
    //Since both the parts list and inspection sheet use the same database table for the parts sections, here we can see if the sheets exists before deciding which sheet ID to use
    public function checkIfExists($pcSheetID){
        global $db;
        $results = false;
        $sql = "SELECT * FROM Component_Table WHERE sheetID = :sheetID";
        $binds = array(
            ":sheetID"=>$pcSheetID
        );
        
        $stmt = $db->prepare($sql);
        if($stmt->execute($binds) && $stmt->rowCount() > 0)
            $results = true;
        elseif($stmt->rowCount() == 0)
            $results = false;

        return $results;
    }

    //Retrieve the component table record for the given work order number. 
    public function getComponentsList($woNum){
        global $db;

        $results = [];

        $sql = "SELECT sheetID,
                       woNum,
                       boardingLadder,
                       boardingRamp,
                       reflectiveTape,
                       seaAnchor,
                       innerOuterLifeLine,
                       seaLightInner,
                       seaLightOuter,
                       floatingKnife,
                       heavingLine,
                       painterAssembly,
                       rightingStraps,
                       ballastBags,
                       doubleFloor,
                       canopy,
                       rainwaterCollector,
                       canopySupportTube,
                       cylinderPouch,
                       cylinderHeadCover,
                       inflationCylinder,
                       cylinderValve,
                       cvSerialNum,
                       cylinderPullCable,
                       cylinderValveAdapter,
                       cylinderHydroTest,
                       cylinderRefill,
                       inflationHose,
                       inletValvePoppetAssembly,
                       toppingUpValves,
                       prvValve,
                       prvPlugs,
                       valise,
                       vacuumBag,
                       container,
                       valiseIDPlacard,
                       valiseLabels,
                       containerGasket,
                       containerSealTape,
                       painterPlug,
                       containerBurstingStrap,
                       solasID,
                       cradle,
                       firingHead,
                       firingHeadSerialNum,
                       userID_Sig1,
                       userID_Sig2,
                       userID_Sig1_Filepath,
                       userID_Sig2_Filepath FROM Component_Table WHERE woNum = :woNum"; 

        $binds = array(
            ":woNum" => $woNum
        );

        $stmt = $db->prepare($sql);
        if($stmt->execute($binds) && $stmt->rowCount() > 0)
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }

    //Add a new record to the component table. With all add/Updates, we use the properties filled in the instantiated object, so we dont have to pass anything to this method. 
    public function AddNewComponentsList(){
        global $db;

        $results = "";

        $sql = "INSERT INTO Component_Table 
                SET sheetID = :sheetID,
                    woNum = :woNum,
                    boardingLadder = :boardingLadder,
                    boardingRamp = :boardingRamp,
                    reflectiveTape = :reflectiveTape,
                    seaAnchor = :seaAnchor,
                    innerOuterLifeLine = :innerOuterLifeLine,
                    seaLightInner = :seaLightInner,
                    seaLightOuter = :seaLightOuter,
                    floatingKnife = :floatingKnife,
                    heavingLine = :heavingLine,
                    painterAssembly = :painterAssembly,
                    rightingStraps = :rightingStraps,
                    ballastBags = :ballastBags,
                    doubleFloor = :doubleFloor,
                    canopy = :canopy,
                    rainwaterCollector = :rainwaterCollector,
                    canopySupportTube = :canopySupportTube,
                    cylinderPouch = :cylinderPouch,
                    cylinderHeadCover = :cylinderHeadCover,
                    inflationCylinder = :inflationCylinder,
                    cylinderValve = :cylinderValve,
                    cvSerialNum = :cvSerialNum,
                    cylinderPullCable = :cylinderPullCable,
                    cylinderValveAdapter = :cylinderValveAdapter,
                    cylinderHydroTest = :cylinderHydroTest,
                    cylinderRefill = :cylinderRefill,
                    inflationHose = :inflationHose,
                    inletValvePoppetAssembly = :inletValvePoppetAssembly,
                    toppingUpValves = :toppingUpValves,
                    prvValve = :prvValve,
                    prvPlugs = :prvPlugs,
                    valise = :valise,
                    vacuumBag = :vacuumBag,
                    container = :container,
                    valiseIDPlacard = :valiseIDPlacard,
                    valiseLabels = :valiseLabels,
                    containerGasket = :containerGasket,
                    containerSealTape = :containerSealTape,
                    painterPlug = :painterPlug,
                    containerBurstingStrap = :containerBurstingStrap,
                    solasID = :solasID,
                    cradle = :cradle,
                    firingHead = :firingHead,
                    firingHeadSerialNum = :firingHeadSerialNum,
                    userID_Sig1 = :userID_Sig1,
                    userID_Sig2 = :userID_Sig2,
                    userID_Sig1_Filepath = :userID_Sig1_Filepath,
                    userID_Sig2_Filepath = :userID_Sig2_Filepath"; 
                $binds = array(
                    ":sheetID" => $this->sheetID,
                    ":woNum" => $this->woNum,
                    ":boardingLadder" => $this->boardingLadder,
                    ":boardingRamp" => $this->boardingRamp,
                    ":reflectiveTape" => $this->reflectiveTape,
                    ":seaAnchor" => $this->seaAnchor,
                    ":innerOuterLifeLine" => $this->innerOuterLifeLine,
                    ":seaLightInner" => $this->seaLightInner,
                    ":seaLightOuter" => $this->seaLightOuter,
                    ":floatingKnife" => $this->floatingKnife,
                    ":heavingLine" => $this->heavingLine,
                    ":painterAssembly" => $this->painterAssembly,
                    ":rightingStraps" => $this->rightingStraps,
                    ":ballastBags" => $this->ballastBags,
                    ":doubleFloor" => $this->doubleFloor,
                    ":canopy" => $this->canopy,
                    ":rainwaterCollector" => $this->rainwaterCollector,
                    ":canopySupportTube" => $this->canopySupportTube,
                    ":cylinderPouch" => $this->cylinderPouch,
                    ":cylinderHeadCover" => $this->cylinderHeadCover,
                    ":inflationCylinder" => $this->inflationCylinder,
                    ":cylinderValve" => $this->cylinderValve,
                    ":cvSerialNum" => $this->cvSerialNum,
                    ":cylinderPullCable" => $this->cylinderPullCable,
                    ":cylinderValveAdapter" => $this->cylinderValveAdapter,
                    ":cylinderHydroTest" => $this->cylinderHydroTest,
                    ":cylinderRefill" => $this->cylinderRefill,
                    ":inflationHose" => $this->inflationHose,
                    ":inletValvePoppetAssembly" => $this->inletValvePoppetAssembly,
                    ":toppingUpValves" => $this->toppingUpValves,
                    ":prvValve" => $this->prvValve,
                    ":prvPlugs" => $this->prvPlugs,
                    ":valise" => $this->valise,
                    ":vacuumBag" => $this->vacuumBag,
                    ":container" => $this->container,
                    ":valiseIDPlacard" => $this->valiseIDPlacard,
                    ":valiseLabels" => $this->valiseLabels,
                    ":containerGasket" => $this->containerGasket,
                    ":containerSealTape" => $this->containerSealTape,
                    ":painterPlug" => $this->painterPlug,
                    ":containerBurstingStrap" => $this->containerBurstingStrap,
                    ":solasID" => $this->solasID,
                    ":cradle" => $this->cradle,
                    ":firingHead" => $this->firingHead,
                    ":firingHeadSerialNum" => $this->firingHeadSerialNum,
                    ":userID_Sig1" => $this->userID_Sig1,
                    ":userID_Sig2" => $this->userID_Sig2,
                    ":userID_Sig1_Filepath" => $this->userID_Sig1_Filepath,
                    ":userID_Sig2_Filepath" => $this->userID_Sig2_Filepath
                );

            $stmt = $db->prepare($sql);
            if($stmt->execute($binds) && $stmt->rowCount() > 0)
                $results = "Component List Added";

            return $results;
    }

    //Update Component table
    public function updateComponentsList(){
        global $db;

        $results = "";

        $sql = "UPDATE Component_Table 
                SET boardingLadder = :boardingLadder,
                    boardingRamp = :boardingRamp,
                    reflectiveTape = :reflectiveTape,
                    seaAnchor = :seaAnchor,
                    innerOuterLifeLine = :innerOuterLifeLine,
                    seaLightInner = :seaLightInner,
                    seaLightOuter = :seaLightOuter,
                    floatingKnife = :floatingKnife,
                    heavingLine = :heavingLine,
                    painterAssembly = :painterAssembly,
                    rightingStraps = :rightingStraps,
                    ballastBags = :ballastBags,
                    doubleFloor = :doubleFloor,
                    canopy = :canopy,
                    rainwaterCollector = :rainwaterCollector,
                    canopySupportTube = :canopySupportTube,
                    cylinderPouch = :cylinderPouch,
                    cylinderHeadCover = :cylinderHeadCover,
                    inflationCylinder = :inflationCylinder,
                    cylinderValve = :cylinderValve,
                    cvSerialNum = :cvSerialNum,
                    cylinderPullCable = :cylinderPullCable,
                    cylinderValveAdapter = :cylinderValveAdapter,
                    cylinderHydroTest = :cylinderHydroTest,
                    cylinderRefill = :cylinderRefill,
                    inflationHose = :inflationHose,
                    inletValvePoppetAssembly = :inletValvePoppetAssembly,
                    toppingUpValves = :toppingUpValves,
                    prvValve = :prvValve,
                    prvPlugs = :prvPlugs,
                    valise = :valise,
                    vacuumBag = :vacuumBag,
                    container = :container,
                    valiseIDPlacard = :valiseIDPlacard,
                    valiseLabels = :valiseLabels,
                    containerGasket = :containerGasket,
                    containerSealTape = :containerSealTape,
                    painterPlug = :painterPlug,
                    containerBurstingStrap = :containerBurstingStrap,
                    solasID = :solasID,
                    cradle = :cradle,
                    firingHead = :firingHead,
                    firingHeadSerialNum = :firingHeadSerialNum,
                    userID_Sig1 = :userID_Sig1,
                    userID_Sig2 = :userID_Sig2,
                    userID_Sig1_Filepath = :userID_Sig1_Filepath,
                    userID_Sig2_Filepath = :userID_Sig2_Filepath
                    WHERE sheetID = :sheetID AND woNum = :woNum"; 
                $binds = array(
                    ":sheetID" => $this->sheetID,
                    ":boardingLadder" => $this->boardingLadder,
                    ":boardingRamp" => $this->boardingRamp,
                    ":reflectiveTape" => $this->reflectiveTape,
                    ":seaAnchor" => $this->seaAnchor,
                    ":innerOuterLifeLine" => $this->innerOuterLifeLine,
                    ":seaLightInner" => $this->seaLightInner,
                    ":seaLightOuter" => $this->seaLightOuter,
                    ":floatingKnife" => $this->floatingKnife,
                    ":heavingLine" => $this->heavingLine,
                    ":painterAssembly" => $this->painterAssembly,
                    ":rightingStraps" => $this->rightingStraps,
                    ":ballastBags" => $this->ballastBags,
                    ":doubleFloor" => $this->doubleFloor,
                    ":canopy" => $this->canopy,
                    ":rainwaterCollector" => $this->rainwaterCollector,
                    ":canopySupportTube" => $this->canopySupportTube,
                    ":cylinderPouch" => $this->cylinderPouch,
                    ":cylinderHeadCover" => $this->cylinderHeadCover,
                    ":inflationCylinder" => $this->inflationCylinder,
                    ":cylinderValve" => $this->cylinderValve,
                    ":cvSerialNum" => $this->cvSerialNum,
                    ":cylinderPullCable" => $this->cylinderPullCable,
                    ":cylinderValveAdapter" => $this->cylinderValveAdapter,
                    ":cylinderHydroTest" => $this->cylinderHydroTest,
                    ":cylinderRefill" => $this->cylinderRefill,
                    ":inflationHose" => $this->inflationHose,
                    ":inletValvePoppetAssembly" => $this->inletValvePoppetAssembly,
                    ":toppingUpValves" => $this->toppingUpValves,
                    ":prvValve" => $this->prvValve,
                    ":prvPlugs" => $this->prvPlugs,
                    ":valise" => $this->valise,
                    ":vacuumBag" => $this->vacuumBag,
                    ":container" => $this->container,
                    ":valiseIDPlacard" => $this->valiseIDPlacard,
                    ":valiseLabels" => $this->valiseLabels,
                    ":containerGasket" => $this->containerGasket,
                    ":containerSealTape" => $this->containerSealTape,
                    ":painterPlug" => $this->painterPlug,
                    ":containerBurstingStrap" => $this->containerBurstingStrap,
                    ":solasID" => $this->solasID,
                    ":cradle" => $this->cradle,
                    ":firingHead" => $this->firingHead,
                    ":firingHeadSerialNum" => $this->firingHeadSerialNum,
                    ":woNum" => $this->woNum,
                    ":userID_Sig1" => $this->userID_Sig1,
                    ":userID_Sig2" => $this->userID_Sig2,
                    ":userID_Sig1_Filepath" => $this->userID_Sig1_Filepath,
                    ":userID_Sig2_Filepath" => $this->userID_Sig2_Filepath
                );

        $stmt = $db->prepare($sql);
        if($stmt->execute($binds) && $stmt->rowCount() > 0)
            $results = "Component List Updated";

        return $results;
    }
    //get the survival record. Instead of work order number, survival and dated item tables are linked to their component table through sheetID
    public function getSurvivalList($sheetID){
        global $db;

        $results = [];

        $sql = "SELECT  sheetID,
                        equipmentBag,
                        handPumpHoseAdapter,
                        sealingPlugs,
                        spareSeaAnchor,
                        instructions,
                        paddles,
                        sponges,
                        canOpener,
                        signalWhistles,
                        signalMirror,
                        fishingKit,
                        flashlight,
                        spareBulb,
                        drinkingCup,
                        jackKnife,
                        seaSickBags,
                        thermalProtectiveAids,
                        firstAidKit,
                        repairKit,
                        desalinator,
                        bailer,
                        epirb FROM Survival_Table WHERE sheetID = :sheetID"; 
                $binds = array(
                    ":sheetID" => $sheetID
                );

            $stmt = $db->prepare($sql);
            if($stmt->execute($binds) && $stmt->rowCount() > 0)
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $results;
    }

    //Add a new Survival record
    public function AddNewSurvivalList(){
        global $db;

        $results = "";

        $sql = "INSERT INTO Survival_Table 
                SET sheetID = :sheetID,
                    woNum = :woNum,
                    equipmentBag = :equipmentBag,
                    handPumpHoseAdapter = :handPumpHoseAdapter,
                    sealingPlugs = :sealingPlugs,
                    spareSeaAnchor = :spareSeaAnchor,
                    instructions = :instructions,
                    paddles = :paddles,
                    sponges = :sponges,
                    canOpener = :canOpener,
                    signalWhistles = :signalWhistles,
                    signalMirror = :signalMirror,
                    fishingKit = :fishingKit,
                    flashlight = :flashlight,
                    spareBulb = :spareBulb,
                    drinkingCup = :drinkingCup,
                    jackKnife = :jackKnife,
                    seaSickBags = :seaSickBags,
                    thermalProtectiveAids = :thermalProtectiveAids,
                    firstAidKit = :firstAidKit,
                    repairKit = :repairKit,
                    desalinator = :desalinator,
                    bailer = :bailer,
                    epirb = :epirb"; 
                $binds = array(
                    ":sheetID" => $this->sheetID,
                    ":woNum" => $this->woNum,
                    ":equipmentBag" => $this->equipmentBag,
                    ":handPumpHoseAdapter" => $this->handPumpHoseAdapter,
                    ":sealingPlugs" => $this->sealingPlugs,
                    ":spareSeaAnchor" => $this->spareSeaAnchor,
                    ":instructions" => $this->instructions,
                    ":paddles" => $this->paddles,
                    ":sponges" => $this->sponges,
                    ":canOpener" => $this->canOpener,
                    ":signalWhistles" => $this->signalWhistles,
                    ":signalMirror" => $this->signalMirror,
                    ":fishingKit" => $this->fishingKit,
                    ":flashlight" => $this->flashlight,
                    ":spareBulb" => $this->spareBulb,
                    ":drinkingCup" => $this->drinkingCup,
                    ":jackKnife" => $this->jackKnife,
                    ":seaSickBags" => $this->seaSickBags,
                    ":thermalProtectiveAids" => $this->thermalProtectiveAids,
                    ":firstAidKit" => $this->firstAidKit,
                    ":repairKit" => $this->repairKit,
                    ":desalinator" => $this->desalinator,
                    ":bailer" => $this->bailer,
                    ":epirb" => $this->epirb                    
                );

            $stmt = $db->prepare($sql);
            if($stmt->execute($binds) && $stmt->rowCount() > 0)
                $results = "Survival List Add";

            return $results;
    }

    //Update survival record
    public function UpdateSurvivalList(){
        global $db;

        $results = "";

        $sql = "UPDATE Survival_Table 
                SET equipmentBag = :equipmentBag,
                    handPumpHoseAdapter = :handPumpHoseAdapter,
                    sealingPlugs = :sealingPlugs,
                    spareSeaAnchor = :spareSeaAnchor,
                    instructions = :instructions,
                    paddles = :paddles,
                    sponges = :sponges,
                    canOpener = :canOpener,
                    signalWhistles = :signalWhistles,
                    signalMirror = :signalMirror,
                    fishingKit = :fishingKit,
                    flashlight = :flashlight,
                    spareBulb = :spareBulb,
                    drinkingCup = :drinkingCup,
                    jackKnife = :jackKnife,
                    seaSickBags = :seaSickBags,
                    thermalProtectiveAids = :thermalProtectiveAids,
                    firstAidKit = :firstAidKit,
                    repairKit = :repairKit,
                    desalinator = :desalinator,
                    bailer = :bailer,
                    epirb = :epirb WHERE sheetID = :sheetID"; 
                $binds = array(
                    ":equipmentBag" => $this->equipmentBag,
                    ":handPumpHoseAdapter" => $this->handPumpHoseAdapter,
                    ":sealingPlugs" => $this->sealingPlugs,
                    ":spareSeaAnchor" => $this->spareSeaAnchor,
                    ":instructions" => $this->instructions,
                    ":paddles" => $this->paddles,
                    ":sponges" => $this->sponges,
                    ":canOpener" => $this->canOpener,
                    ":signalWhistles" => $this->signalWhistles,
                    ":signalMirror" => $this->signalMirror,
                    ":fishingKit" => $this->fishingKit,
                    ":flashlight" => $this->flashlight,
                    ":spareBulb" => $this->spareBulb,
                    ":drinkingCup" => $this->drinkingCup,
                    ":jackKnife" => $this->jackKnife,
                    ":seaSickBags" => $this->seaSickBags,
                    ":thermalProtectiveAids" => $this->thermalProtectiveAids,
                    ":firstAidKit" => $this->firstAidKit,
                    ":repairKit" => $this->repairKit,
                    ":desalinator" => $this->desalinator,
                    ":bailer" => $this->bailer,
                    ":epirb" => $this->epirb,
                    ":sheetID" => $this->sheetID
                );

            $stmt = $db->prepare($sql);
            if($stmt->execute($binds) && $stmt->rowCount() > 0)
                $results = "Survival List Updated";

            return $results;
    }

    //Get Dated Items for associated SheetID
    public function getDatedItems($sheetID){
        global $db;

        $results = [];

        $sql = "SELECT  rations,
                        water,
                        burnCream,
                        aspirin,
                        iodineSwabs,
                        eyeWash,
                        seasickPills,
                        handFlares,
                        parachuteFlares,
                        chooseSmoke,
                        dCellBatteries,
                        repairKitCement,
                        seaLightCells,
                        hydrostaticRelease,
                        epirbBattery FROM Dated_Items_Table WHERE sheetID = :sheetID"; 
                $binds = array(
                    ":sheetID" => $sheetID
                );

            $stmt = $db->prepare($sql);
            if($stmt->execute($binds) && $stmt->rowCount() > 0)
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $results;
    }

    //Add new dated items record
    public function AddNewDatedItemsList(){
        global $db;

        $results = "";

        $sql = "INSERT INTO Dated_Items_Table 
                SET sheetID = :sheetID,
                    woNum = :woNum,
                    rations = :rations,
                    water = :water,
                    burnCream = :burnCream,
                    aspirin = :aspirin,
                    iodineSwabs = :iodineSwabs,
                    eyeWash = :eyeWash,
                    seasickPills = :seasickPills,
                    handFlares = :handFlares,
                    parachuteFlares = :parachuteFlares,
                    chooseSmoke = :chooseSmoke,
                    dCellBatteries = :dCellBatteries,
                    repairKitCement = :repairKitCement,
                    seaLightCells = :seaLightCells,
                    hydrostaticRelease = :hydrostaticRelease,
                    epirbBattery = :epirbBattery"; 
                $binds = array(
                    ":sheetID"=> $this->sheetID,
                    ":woNum" => $this->woNum,
                    ":rations" => $this->rations,
                    ":water" => $this->water,
                    ":burnCream" => $this->burnCream,
                    ":aspirin" => $this->aspirin,
                    ":iodineSwabs" => $this->iodineSwabs,
                    ":eyeWash" => $this->eyeWash,
                    ":seasickPills" => $this->seasickPills,
                    ":handFlares" => $this->handFlares,
                    ":parachuteFlares" => $this->parachuteFlares,
                    ":chooseSmoke" => $this->chooseSmoke,
                    ":dCellBatteries" => $this->dCellBatteries,
                    ":repairKitCement" => $this->repairKitCement,
                    ":seaLightCells" => $this->seaLightCells,
                    ":hydrostaticRelease" => $this->hydrostaticRelease,
                    ":epirbBattery" => $this->epirbBattery              
                );

            $stmt = $db->prepare($sql);
            if($stmt->execute($binds) && $stmt->rowCount() > 0)
                $results = "Dated Items List Add";

            return $results;
    }

    //Update Dated Items record. 
    public function UpdateDatedItemsTable(){
        global $db;

        $results = "";

        $sql = "UPDATE Dated_Items_Table 
                SET rations = :rations,
                    water = :water,
                    burnCream = :burnCream,
                    aspirin = :aspirin,
                    iodineSwabs = :iodineSwabs,
                    eyeWash = :eyeWash,
                    seasickPills = :seasickPills,
                    handFlares = :handFlares,
                    parachuteFlares = :parachuteFlares,
                    chooseSmoke = :chooseSmoke,
                    dCellBatteries = :dCellBatteries,
                    repairKitCement = :repairKitCement,
                    seaLightCells = :seaLightCells,
                    hydrostaticRelease = :hydrostaticRelease,
                    epirbBattery = :epirbBattery WHERE sheetID = :sheetID"; 
                $binds = array(
                    ":rations" => $this->rations,
                    ":water" => $this->water,
                    ":burnCream" => $this->burnCream,
                    ":aspirin" => $this->aspirin,
                    ":iodineSwabs" => $this->iodineSwabs,
                    ":eyeWash" => $this->eyeWash,
                    ":seasickPills" => $this->seasickPills,
                    ":handFlares" => $this->handFlares,
                    ":parachuteFlares" => $this->parachuteFlares,
                    ":chooseSmoke" => $this->chooseSmoke,
                    ":dCellBatteries" => $this->dCellBatteries,
                    ":repairKitCement" => $this->repairKitCement,
                    ":seaLightCells" => $this->seaLightCells,
                    ":hydrostaticRelease" => $this->hydrostaticRelease,
                    ":epirbBattery" => $this->epirbBattery,
                    ":sheetID"=> $this->sheetID              
                );

            $stmt = $db->prepare($sql);
            if($stmt->execute($binds) && $stmt->rowCount() > 0)
                $results = "Dated Items List Updated";

            return $results;
    }
}
?>