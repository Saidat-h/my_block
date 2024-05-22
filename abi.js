
    const abi= [
      
      {
        "anonymous": false,
        "inputs": [
          {
            "indexed": true,
            "internalType": "string",
            "name": "vin",
            "type": "string"
          },
          {
            "indexed": false,
            "internalType": "uint256",
            "name": "mileage",
            "type": "uint256"
          },
          {
            "indexed": false,
            "internalType": "uint256",
            "name": "registrationTime",
            "type": "uint256"
          }
        ],
        "name": "CarRegistered",
        "type": "event"
      },
      {
        "anonymous": false,
        "inputs": [
          {
            "indexed": true,
            "internalType": "string",
            "name": "vin",
            "type": "string"
          },
          {
            "indexed": false,
            "internalType": "uint256",
            "name": "newMileage",
            "type": "uint256"
          },
          {
            "indexed": false,
            "internalType": "uint256",
            "name": "timestamp",
            "type": "uint256"
          }
        ],
        "name": "MileageUpdated",
        "type": "event"
      },
      {
        "constant": false,
        "inputs": [
          {
            "internalType": "string",
            "name": "vin",
            "type": "string"
          },
          {
            "internalType": "uint256",
            "name": "initialMileage",
            "type": "uint256"
          },
          {
            "internalType": "uint256",
            "name": "registrationTime",
            "type": "uint256"
          }
        ],
        "name": "registerCar",
        "outputs": [],
        "payable": false,
        "stateMutability": "nonpayable",
        "type": "function"
      },
      {
        "constant": false,
        "inputs": [
          {
            "internalType": "string",
            "name": "vin",
            "type": "string"
          },
          {
            "internalType": "uint256",
            "name": "newMileage",
            "type": "uint256"
          },
          {
            "internalType": "uint256",
            "name": "timestamp",
            "type": "uint256"
          }
        ],
        "name": "updateMileage",
        "outputs": [],
        "payable": false,
        "stateMutability": "nonpayable",
        "type": "function"
      },
      {
        "constant": true,
        "inputs": [
          {
            "internalType": "string",
            "name": "vin",
            "type": "string"
          }
        ],
        "name": "getMileageHistory",
        "outputs": [
          {
            "internalType": "uint256[]",
            "name": "",
            "type": "uint256[]"
          },
          {
            "internalType": "uint256[]",
            "name": "",
            "type": "uint256[]"
          }
        ],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
      }
  
    ]