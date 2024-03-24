<?php
class Contact
{
    private $contacts;
    private $contactStatus;
    private $user;
    private $dbh;

    public function __construct()
    {
        $this->contacts = array();
        $this->contactStatus = array();
        $this->user = "";
        $this->dbh = new PDO("mysql:host=ftp.thepetsmode.com;dbname=gamerfre_erasmus;charset=utf8", "gamerfre_erasmus_user", "3r4sMus_2024");
    }

    public function signIn($username, $password)
    {
        try {
            $stmt = $this->dbh->prepare("SELECT * FROM USERS WHERE USERNAME = '" . $username . "' AND PASSWORD = '" . $password . "';");
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result) {
                if ($stmt->errorCode() === '00000') {
                    switch (count($result)) {
                        case 0:
                            $this->dbh = null;
                            return null;
                        default:
                            $this->dbh = null;
                            return $result[0]["USERTYPE"];
                    }
                }
            } else {
                $this->dbh = null;
                return null;
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    public function selectAllContacts()
    {
        try {
            $stmt = $this->dbh->prepare("SELECT * FROM CONTACTS;");
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result) {
                if ($stmt->errorCode() === '00000') {
                    foreach ($result as $res) {
                        $this->contacts[] = $res;
                    }

                    return $this->contacts;
                } else {
                    return null;
                }
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    public function selectAllContactsStatus()
    {
        try {
            $stmt = $this->dbh->prepare("SELECT * FROM CONTACTS_STATE");
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result) {
                if ($stmt->errorCode() === '00000') {
                    foreach ($result as $res) {
                        $this->contactStatus[] = $res;
                    }

                    return $this->contactStatus;
                } else {
                    return null;
                }
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    public function selectContact($id)
    {
        try {
            $stmt = $this->dbh->prepare("SELECT * FROM CONTACTS WHERE ID=" . $id . ";");
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result) {
                if ($stmt->errorCode() === '00000') {
                    foreach ($result as $res) {
                        $contact = $res;
                    }

                    $this->dbh = null;
                    return $contact;
                } else {
                    $this->dbh = null;
                    return false;
                }
            }
        } catch (PDOException $e) {
            $this->dbh = null;
            return false;
        }
    }

    public function selectContactStatus($state)
    {
        try {
            $stmt = $this->dbh->prepare("SELECT * FROM CONTACTS_STATE WHERE ID=" . $state . ";");
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result) {
                if ($stmt->errorCode() === '00000') {
                    foreach ($result as $res) {
                        $contactStatus = $res;
                    }

                    return $contactStatus;
                } else {
                    return false;
                }
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    public function selectContactStatusByState($state)
    {
        try {
            $stmt = $this->dbh->prepare("SELECT * FROM CONTACTS_STATE WHERE STATE='" . $state . "';");
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result) {
                if ($stmt->errorCode() === '00000') {
                    foreach ($result as $res) {
                        $this->contactStatus[] = $res;
                    }

                    return $this->contactStatus;
                } else {
                    return false;
                }
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    public function addContact($statusID, $person, $enterprise, $country, $csv, $email, $phone, $web, $otherMedia, $record)
    {
        try {
            $stmt = $this->dbh->prepare("INSERT INTO CONTACTS (STATE, PERSON, ENTERPRISE, COUNTRY, CSV, EMAIL, PHONE, WEB, OTHER_MEDIA, RECORD) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$statusID, $person, $enterprise, $country, $csv, $email, $phone, $web, $otherMedia, $record]);

            $this->dbh = null;
            return true;
        } catch (PDOException $e) {
            $this->dbh = null;
            return false;
        }
    }

    public function addContactStatus($status, $color)
    {
        try {
            $stmt = $this->dbh->prepare("INSERT INTO CONTACTS_STATE (STATE, COLOR) VALUES (?, ?)");
            $stmt->execute([$status, $color]);

            $lastId = $this->dbh->lastInsertId();
            return $lastId;
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function editContact($id, $stateID, $person, $enterprise, $country, $csv, $email, $phone, $web, $otherMedia, $record, $state)
    {
        try {
            $returnValue = true;

            $stmt = $this->dbh->prepare("UPDATE CONTACTS SET PERSON=?, ENTERPRISE=?, COUNTRY=?, CSV=?, EMAIL=?, PHONE=?, WEB=?, OTHER_MEDIA=?, RECORD=? WHERE ID=?");
            $stmt->execute([$person, $enterprise, $country, $csv, $email, $phone, $web, $otherMedia, $record, $id]);

            $returnValue = true;

            $color = "#ffffff";
            if ($state == "WITHOUT STATE") {
                $color = "#ffffff";
            } else if ($state == "START CONTACT") {
                $color = "#b3e5fc";
            } else if ($state == "IN CONTACT") {
                $color = "#c8e6c9";
            } else if ($state == "WITHOUT CONTACT") {
                $color = "#bdbdbd";
            } else if ($state == "WAITING ANSWER") {
                $color = "#fff9c4";
            } else if ($state == "PENDING ANSWER") {
                $color = "#ffe0b2";
            } else if ($state == "GOOD RELATIONSHIP") {
                $color = "#a5d6a7";
            } else if ($state == "BAD RELATIONSHIP") {
                $color = "#ef9a9a";
            }

            $stmt = $this->dbh->prepare("UPDATE CONTACTS_STATE SET STATE=?, COLOR=? WHERE ID=?");
            $stmt->execute([$state, $color, $stateID]);

            $returnValue = true;

            $this->dbh = null;
            return $returnValue;
        } catch (PDOException $e) {
            $this->dbh = null;
            return false;
        }
    }

    public function editContactStatus($id, $status, $color)
    {
        try {
            $stmt = $this->dbh->prepare("UPDATE CONTACTS SET STATUS=?, COLOR=? WHERE ID=?");
            $stmt->execute([$status, $color, $id]);

            if ($stmt->rowCount() > 0) {
                $this->dbh = null;
                return true;
            } else {
                $this->dbh = null;
                return false;
            }
        } catch (PDOException $e) {
            $this->dbh = null;
            return false;
        }
    }

    public function deleteContact($id, $stateID)
    {
        try {
            $deleteStatusResult = $this->deleteContactStatus($stateID);
            $stmt = $this->dbh->prepare("DELETE FROM CONTACTS WHERE ID = ?");
            $stmt->execute([$id]);

            if ($stmt->errorCode() === '00000' && $deleteStatusResult) {
                $this->dbh = null;
                return true;
            } else {
                $this->dbh = null;
                return false;
            }
        } catch (PDOException $e) {
            $this->dbh = null;
            return false;
        }
    }

    public function deleteMultipleContacts($contacts)
    {
        try {
            $allDeleted = true;

            foreach ($contacts as $contact) {
                $contact = explode(",", $contact);
                $id = $contact[0];
                $stateID = $contact[1];

                $deleteStatusResult = $this->deleteContactStatus($stateID);
                $stmt = $this->dbh->prepare("DELETE FROM CONTACTS WHERE ID = ?");
                $stmt->execute([$id]);

                if ($stmt->errorCode() === '00000' && $deleteStatusResult)
                    $deleted = true;
                else
                    $deleted = false;

                if (!$deleted)
                    $allDeleted = false;
            }

            $this->dbh = null;
            return $allDeleted;
        } catch (PDOException $e) {
            $this->dbh = null;
            return false;
        }
    }

    public function deleteContactStatus($stateID)
    {
        try {
            $stmt = $this->dbh->prepare("DELETE FROM CONTACTS_STATE WHERE ID = ?");
            $stmt->execute([$stateID]);

            if ($stmt->errorCode() === '00000') {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getAllContactsType() 
    {
        try {
            $stmt = $this->dbh->prepare("SELECT  FROM CONTACTS");
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result) {
                if ($stmt->errorCode() === '00000') {
                    return $result;
                } else {
                    return null;
                }
            }
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>