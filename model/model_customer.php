<?php 
/***************************************************************************************************************************************************
 * 
 * Created a Class for both Customer and Vessel, with associated Database Methods as part of the class. This means no passing data to the methods
 * Just calling them from the instantiated object on the page using the properties we've already filled out.  
 * 
************************************************************************************************************************************************** */


include('db.php');

//Create Customer Class
class Customer {
    //Customer properties, matches customer lookup table in DB. 
    public $customerID = "";
    public $customerName = "";
    public $customerAddress = ""; 
    public $customerAddress2 = "";
    public $customerCity = "";
    public $customerState = ""; 
    public $customerZipCode = "";
    public $customerDeliveryAddress = "";
    public $customerDeliveryAddress2 = "";
    public $customerDeliveryCity = "";
    public $customerDeliveryState = ""; 
    public $customerDeliveryZipCode = "";
    public $customerEmail = "";
    public $customerPhone = "";

    //On the customers page, we populate dropdowns using this. It's static so we can do it even if we don't have a Cust Obj instantiated. 
    public static $states = ["AK", "AL", "AR", "AS", "AZ", "CA", "CO", "CT", "DC", "DE", "FL", "GA", "GU", "HI", "IA", "ID", "IL", "IN", "KS", "KY", 
                      "LA", "MA", "MD", "ME", "MI", "MN", "MO", "MP", "MS", "MT", "NC", "ND", "NE", "NH", "NJ", "NM", "NV", "NY", "OH", "OK", 
                      "OR", "PA", "PR", "RI", "SC", "SD", "TN", "TX", "UM", "UT", "VA", "VI", "VT", "WA", "WI", "WV", "WY"];

    //retrieve customers. If $id is null/empty, returns all for displaying on table. Otherwise grabs the one customer for filling info. 
    public function getCustomers($id){
        global $db;

        $results = [];

        $sql = "SELECT customerID, customerName, customerAddress, customerAddress2, customerCity, customerState, customerZipCode,
                customerDeliveryAddress, customerDeliveryAddress2, customerDeliveryCity, customerDeliveryState, customerDeliveryZipCode,
                customerEmail, customerPhone FROM Customer_Lookup WHERE 0=0";
        if ($id != "") {
            $sql .= " AND customerID LIKE :customerID";
            $binds['customerID'] = '%'.$id.'%';
        }
        else 
            $binds = [];

        $stmt = $db->prepare($sql);
        if($stmt->execute($binds) && $stmt->rowCount() > 0)
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }

    //Add a new customer. Takes an object that will have all the properties needed to fill out the table. 
    public function addCustomer(){
        global $db;
        $results = "";

        $sql = "INSERT INTO Customer_Lookup SET customerName = :customerName, customerAddress = :customerAddress, 
                customerAddress2 = :customerAddress2, customerCity = :customerCity, customerState = :customerState, customerZipCode = :customerZipCode,
                customerDeliveryAddress = :customerDeliveryAddress, customerDeliveryAddress2 = :customerDeliveryAddress2, customerDeliveryCity = :customerDeliveryCity,
                customerDeliveryState = :customerDeliveryState, customerDeliveryZipCode = :customerDeliveryZipCode, customerEmail = :customerEmail, 
                customerPhone = :customerPhone;"; 

        $binds = array(
            ":customerName" => $this->customerName,
            ":customerAddress" => $this->customerAddress,
            ":customerAddress2" => $this->customerAddress2,
            ":customerCity" => $this->customerCity,
            ":customerState" => $this->customerState,
            ":customerZipCode" => $this->customerZipCode,
            ":customerDeliveryAddress" => $this->customerDeliveryAddress,
            ":customerDeliveryAddress2" => $this->customerDeliveryAddress2,
            ":customerDeliveryCity" => $this->customerDeliveryCity,
            ":customerDeliveryState" => $this->customerDeliveryState,
            ":customerDeliveryZipCode" => $this->customerDeliveryZipCode,
            ":customerEmail" => $this->customerEmail,
            ":customerPhone" => $this->customerPhone
        );

        $stmt = $db->prepare($sql);
        if($stmt->execute($binds) && $stmt->rowCount() > 0)
            $results = $db->lastInsertId(); //HERE

        return $results;
    }

    //Update the Customer
    public function updateCustomer(){
        global $db;
        $results = "";

        $sql = "UPDATE Customer_Lookup SET customerName = :customerName, customerAddress = :customerAddress, 
        customerAddress2 = :customerAddress2, customerCity = :customerCity, customerState = :customerState, customerZipCode = :customerZipCode,
        customerDeliveryAddress = :customerDeliveryAddress, customerDeliveryAddress2 = :customerDeliveryAddress2, customerDeliveryCity = :customerDeliveryCity,
        customerDeliveryState = :customerDeliveryState, customerDeliveryZipCode = :customerDeliveryZipCode, customerEmail = :customerEmail, 
        customerPhone = :customerPhone WHERE customerID = :customerID;";

        $binds = array(
            ":customerName" => $this->customerName,
            ":customerAddress" => $this->customerAddress,
            ":customerAddress2" => $this->customerAddress2,
            ":customerCity" => $this->customerCity,
            ":customerState" => $this->customerState,
            ":customerZipCode" => $this->customerZipCode,
            ":customerDeliveryAddress" => $this->customerDeliveryAddress,
            ":customerDeliveryAddress2" => $this->customerDeliveryAddress2,
            ":customerDeliveryCity" => $this->customerDeliveryCity,
            ":customerDeliveryState" => $this->customerDeliveryState,
            ":customerDeliveryZipCode" => $this->customerDeliveryZipCode,
            ":customerEmail" => $this->customerEmail,
            ":customerPhone" => $this->customerPhone, 
            ":customerID" => $this->customerID
        );

        $stmt = $db->prepare($sql);
        if($stmt->execute($binds) && $stmt->rowCount() > 0)
            $results = "Customer Updated";

        return $results;
    }
}

//Vessel Class. This is on this page because all vessels will be associated with the customer that owns them. In later versions of this software, we will
//also likely merge the vessel selection/edit page with the customer page. 
Class Vessel 
{
    //Vessel Properties. 
    public $vesselID;
    public $customerID;
    public $vesselName;
    public $vesselModel;
    public $lastInspection;
    public $nextInspection;
    public $imoNum;
    public $callSign;
    public $vesselFlag;
    public $dateOfMfr;
    public $classSociety;
    public $vesselManufacturer;
    public $vesselCapacity;

    //Gets all vessels associated with the customer ID passed to it. Customers can have several Vessels on record. 
    public function getCustomerVessels($customerID){
        global $db;

        $results = [];

        $sql = "SELECT vesselID, customerID, vesselName, vesselModel, lastInspection, nextInspection, imoNum, callSign, vesselFlag, dateOfMfr, classSociety FROM Customer_Vessels_Lookup WHERE 0=0";
        $sql .= " AND customerID LIKE :customerID";
        $binds['customerID'] = '%'.$customerID.'%';

        $stmt = $db->prepare($sql);
        if($stmt->execute($binds) && $stmt->rowCount() > 0){
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //Vessel Model, Manufacturer and Capacity are stored in a separate table. This retrieves that information to send to the page.
            for($i = 0; $i<count($results); $i++){
                $vesselInfo = $this->getVesselInfo($results[$i]['vesselModel']); 
                $results[$i]['vesselManufacturer'] = $vesselInfo[0]['vesselManufacturer'];
                $results[$i]['vesselCapacity'] = $vesselInfo[0]['vesselCapacity'];
            }
        }
        

        return $results;
    }

    //Gets the informatnio for the passed model of vessel, stored in the vessel_lookup_table. 
    public function getVesselInfo($model){
        global $db;

        $results = [];

        $sql = "SELECT vesselManufacturer, vesselCapacity FROM Vessel_Lookup WHERE 0=0";
        $sql .= " AND vesselModel LIKE :vesselModel";
        $binds['vesselModel'] = '%'.$model.'%';

        $stmt = $db->prepare($sql);
        if($stmt->execute($binds) && $stmt->rowCount() > 0){
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        }
        return $results;
    }

    //Adds a new vessel to customer_vessels. 
    public function addNewVessel(){
        global $db;
        $results = "";

        //If the model doesn't exist in the database yet, add it. 
        if($this->getVesselInfo($this->vesselModel) == [])
           $results = $this->addVesselModelToVesselLookup(); 

        $sql = "INSERT INTO Customer_Vessels_Lookup SET vesselID = :vesselID, customerID = :customerID, vesselName = :vesselName, vesselModel = :vesselModel, imoNum = :imoNum, callSign = :callSign, vesselFlag = :vesselFlag, dateOfMfr = :dateOfMfr, lastInspection = :lastInspection, classSociety = :classSociety";
        $binds = array(
            ":vesselID" => $this->vesselID,
            ":customerID" => $this->customerID,
            ":vesselName" => $this->vesselName, 
            ":vesselModel" => $this->vesselModel, 
            ":imoNum" => $this->imoNum, 
            ":callSign" => $this->callSign,  
            ":vesselFlag" => $this->vesselFlag,
            ":dateOfMfr" => $this->dateOfMfr,
            ":lastInspection" => $this->lastInspection,
            ":classSociety" => $this->classSociety            
        );

        $stmt = $db->prepare($sql);
        if($stmt->execute($binds) && $stmt->rowCount() > 0){
            $results = "Vessel Added";
        }
      
        return $results;
    }

    //When adding a new customer vessel, they may add a model that is not currently in the database. If that is the case, this func is called to add it. 
    public function addVesselModelToVesselLookup(){
        global $db;
        $results = "";

        $sql = "INSERT INTO vessel_lookup SET vesselModel = :vesselModel, vesselManufacturer = :vesselManufacturer, vesselCapacity = :vesselCapacity";
        $binds = array(
            ":vesselModel" => $this->vesselModel,
            ":vesselManufacturer" => $this->vesselManufacturer,
            ":vesselCapacity" => $this->vesselCapacity
        );

        $stmt = $db->prepare($sql);
        if($stmt->execute($binds) && $stmt->rowCount() > 0)
            $results = "New Vessel Added";
        
        return $results;
    }

    //Updates the customers vessel. Does not update model, manufacturer, or capacity in the lookup table. 
    public function UpdateVessel(){
        global $db;
        $results = "";

        if($this->getVesselInfo($this->vesselModel) == [])
           $results .= $this->addVesselModelToVesselLookup();

        $sql = "UPDATE Customer_Vessels_Lookup SET customerID = :customerID, vesselName = :vesselName, vesselModel = :vesselModel,  
        imoNum = :imoNum, callSign = :callSign, vesselFlag = :vesselFlag, dateOfMfr = :dateOfMfr, classSociety = :classSociety, lastInspection = :lastInspection WHERE vesselID = :vesselID";
        $binds = array(
            ":customerID" => $this->customerID,
            ":vesselName" => $this->vesselName, 
            ":vesselModel" => $this->vesselModel, 
            ":imoNum" => $this->imoNum, 
            ":callSign" => $this->callSign,  
            ":vesselFlag" => $this->vesselFlag,
            ":dateOfMfr" => $this->dateOfMfr,
            ":classSociety" => $this->classSociety,
            ":lastInspection" => $this->lastInspection,
            ":vesselID" => $this->vesselID
        );

        $stmt = $db->prepare($sql);
        if($stmt->execute($binds) && $stmt->rowCount() > 0){
            $results = "Vessel Updated";
        }
      
        return $results;
    }

}

//The function for filling the state dropdown. 
function fillStateDropdown($custState){
   foreach(Customer::$states as $state){
        if($state == $custState)
            echo "<option value='" . $state . "' selected >" . $state . "</option>";
        else
            echo "<option value='" . $state . "'>" . $state . "</option>";
   }
}

//Get all models from the vessel lookup for displaying in a datalist. 
function getvesselModels(){
    global $db;

    $results = [];

    $sql = "SELECT * FROM Vessel_Lookup WHERE 0=0";

    $stmt = $db->prepare($sql);
    if($stmt->execute() && $stmt->rowCount() > 0){
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return $results;
}
?>