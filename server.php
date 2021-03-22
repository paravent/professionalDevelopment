
  <?php
 
 	// Using a seperate file to handle card details in order to give the ability 
	// for different permissions to be used, away from public eye
 
	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		 
		$ccn = $_REQUEST['ccn']; 
		$cipher = "AES-128-CTR";
		// Use OpenSSl Encryption method 
		$iv_length = openssl_cipher_iv_length($cipher); 
		$options = OPENSSL_ZERO_PADDING; 
		  
		// Non-NULL Initialization Vector for encryption 
		$encryption_iv = "1234567891011121"; 
		  
		// Store the encryption key 
		$encryption_key = "AidensEncryption"; 
		  
		// Use openssl_encrypt() function to encrypt the data 
		$encryption = openssl_encrypt($ccn, $cipher, 
					$encryption_key, $options, $encryption_iv); 
		  
		// Display the encrypted string 
		echo "Encrypted String: " . $encryption . "\n"; 
		 
		
		$expyear = $_REQUEST['expyear'];
		$expmonth = $_REQUEST['expmonth'];
		$seccode = $_REQUEST['cvv'];
		$ymd = DateTime::createFromFormat('Y-d-m', $expyear. '-' . $expmonth . '-01')->format('Y-m-d');
		
		

			$servername = "localhost";
			$username = "root";
			$password = "";
			$DBname = "creditcard";

			// Create connection
			$conn = new mysqli($servername, $username, $password, $DBname);

			// Check connection
			if ($conn->connect_error) {
    			die("Connection failed: " . $conn->connect_error);
			}
			echo "Connected successfully";
			
			$data = ("INSERT into card (ccnum, expdate, seccode) VALUES ('" . $encryption . "','" . $ymd . "' , '" . $seccode . "')");
			
			if($conn->query($data) === TRUE){
				echo "new record created";
					// sql to get back last added record
					$sql =  ("select ccnum from card where `#` = (select max(`#`) from card) ");
	
					
					
					if ( $result = $conn -> query($sql)){
						
						if ($result->num_rows > 0) {
							// output data of each row
							while($row = $result->fetch_assoc()) {
								$options = 0; 
								$cipher = "AES-128-CTR";
								// INVESTIGATE IV_LENGTH
								$iv_length = openssl_cipher_iv_length($cipher);
								// Non-NULL Initialization Vector for decryption 
								$decryption_iv = "1234567891011121"; 
				  
								// Store the decryption key 
								$decryption_key = "AidensEncryption"; 
				  
								// Use openssl_decrypt() function to decrypt the data 
								$decrypted=openssl_decrypt ($row['ccnum'], $cipher,  
								$decryption_key, $options, $decryption_iv);
								$str=substr($decrypted,12); 
								header("Location: page2.php?ccnum=".$str); 
			
							}
						} else {
						}
						
					}
						else{
						}
					
				
				}
				else{
					echo "Error" . $data . "<br>" . $conn->error ; 
				}
			}
	
			
	

	?>
	
	
