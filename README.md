# KeyGen Lib – v1.1.0

**KeyGen Library** is an *open source PHP library for random password generation*.  
Want to know more about KeyGen or its creator? [Go to the "About" section](#about)!

## Get Started

[Download the latest release](https://github.com/matiboux/KeyGen-Lib/releases/latest), extract the "KeyGen-Lib/" folder in your working directory and include the **KeyGen-Lib/KeyGen-Core.php** file in your PHP script, and start using it.  
You can also use the **api.php** file as a working example.

### Using Oli

To use this library with [Oli](https://github.com/matiboux/Oli), add the "KeyGen-Lib/" folder directly into the framework **addons/** folder. The library will be automatically included to the website by the framework. You can start using it directly.

## Using the API

The API get its parameters from GET or POST methods, and return a JSON response.
The input parameters are:
 - "numeric" or "num" (boolean) (default: true) / If true, the keygen could can contain numeric characters?
 - "lowercase" or "low" (boolean) (default: true) / If true, the keygen could can contain lowercase characters?
 - "uppercase" or "upp" (boolean) (default: true) / If true, the keygen could can contain uppercase characters?
 - "special" or "spe" (boolean) (default: false) / If true, the keygen could can contain special characters.
 - "length" or "len" (boolean) (default: 12) / Define the keygen length.
 - "redundancy" or "red" (boolean) (default: true) / If true, characters can appear multiple times in the keygen?

The JSON response contains:
 - "error": Indicate if there is an error. If true, "error-infos" will give context about the error.
 - "response": Contains the generated keygen (if nothing went wrong).
 - "parameters": A reminder of the parameters used when generating the keygen.
 - "default-parameters": If true, indicates the parameters were not changed by the user.
 - "forced-redundancy": If true, redundancy was set to false, but was forced to true in order to generate a keygen with the specified length.

### Example

- api.php  
  `{"error": false, "response": "2Bo9PGEU69RV", "parameters": {"length": 12, "numeric": true, "lowercase": true, "uppercase": true, "special": false, "redundancy": true}, "default-parameters": true, "forced-redundancy": false, ...}`
  
- api.php?len=30  
  `{"error": false, "response": "NVWZvarzYia7KETGyHHNYxA7kmLhi9", "parameters": {"length": "30", "numeric": true, "lowercase": true, "uppercase": true, "special": false, "redundancy": true}, "default-parameters": false, "forced-redundancy": false, ...}`
  
- api.php?num=1&low=0&upp=0&spe=0&len=10&red=0  
  `{"error": false, "response": "0147852963", "parameters": {"length": "10", "numeric": "1", "lowercase": "0", "uppercase": "0", "special": "0", "redundancy": "0"}, "default-parameters": false, "forced-redundancy": false, ...}`  
  /!\ **Note**: This **does not** work as a random number generator. It's a random sequence of numerical characters.

---

## License – MIT

Copyright (c) 2017 Matiboux (Mathieu Guérin)  
*You'll find a copy of the MIT license in the **LICENSE** file.*

## About

**KeyGen** is an *open source random password generator* service, also created by Matiboux.  
Since its beginning, the project consisted in providing a password generation service on a website. The initial source code was then open sourced, and an API was created and is publicly available to evfor developers.

[**Oli**](https://github.com/matiboux/Oli) is an *open source PHP framework*, also created by Matiboux.

**Creator & Developer**: Matiboux (Mathieu Guérin)  
Want to get in touch with me? Here's how:
 - **Email**: [matiboux@gmail.com](mailto:matiboux@gmail.com)
 - **Telegram**: [@Matiboux](https://t.me/Matiboux)

---

**Community and feedbacks are everything! Help is always appreciated! <3**