<?php
/**
 * RTO High-Engagement Content Bank Generator (5,000+ Drafts)
 * Covers all RTO app modules:
 * 1. RC Details & Vehicle Search
 * 2. Traffic Challan & Penalties
 * 3. Driving Licence (DL) Search
 * 4. RTO Exam Preparation & Mock Tests
 * 5. Traffic Road Signs & Symbols
 * 6. Traffic Rules & Motor Vehicle Act
 * 7. Daily Fuel Prices (Petrol, Diesel, CNG)
 * 8. Vehicle Resale Value Estimator
 * 9. Fuel Mileage & Road Trip Cost Calculator
 * 10. Car & Bike Loan EMI Calculator
 * 11. Document Vault & Expiry Reminders (PUC, Insurance)
 * 12. Celebrity & VIP Vehicles
 * 13. RTO Offices & Official Forms
 * 14. Hilarious Driving Memes & Relatable Road Hype
 */

error_reporting(E_ALL);
ini_set('memory_limit', '512M');
date_default_timezone_set('Asia/Kolkata');

$db = new mysqli('193.203.184.175', 'u126066279_poster_db', 'Oa2C*X1=', 'u126066279_poster_db');
if ($db->connect_error) {
    die("Database Connection Error: " . $db->connect_error . "\n");
}
$db->set_charset("utf8mb4");

$all_drafts = [];
$seen_uids = [];

function add_draft(&$drafts, &$seen, $title, $body, $cat, $target, $slot = 'ANY') {
    $title = trim($title);
    $body = trim($body);
    $key = mb_strtolower($title . '|' . $body, 'UTF-8');
    $uid = hash('sha256', $key);
    if (isset($seen[$uid])) {
        return false;
    }
    $seen[$uid] = true;
    $drafts[] = [
        'content_uid'    => $uid,
        'title'          => $title,
        'body'           => $body,
        'category_name'  => $cat,
        'target_screen'  => $target,
        'scheduled_slot' => $slot,
        'status'         => 'DRAFT'
    ];
    return true;
}

echo "Generating 5,000+ RTO notifications across all modules...\n";

// =========================================================================
// MODULE 1: RC DETAILS & VEHICLE OWNER SEARCH (Target: RC, ~600 items)
// =========================================================================
$rc_hooks = [
    "Whose %s is parked outside your gate? 🕵️‍♂️",
    "Saw a stunning %s on the highway? 👀",
    "Crush just drove past in a %s? 😉",
    "Buying a second-hand %s today? 🔍",
    "Who owns that loud %s in your society? 🔊",
    "Suspicious %s parked in your neighborhood? 🚨",
    "Thinking of purchasing a pre-owned %s? 🏷️",
    "Met with a minor scratch from a rash %s? 📝",
    "That fancy %s with VIP number plate! ✨",
    "Found an abandoned %s on the roadside? 🛑",
    "Friend flexing a new %s? Fact-check them! 😎",
    "Neighbor bought a shiny %s? Check details! 🚙",
    "Check owner history before buying that %s! ⚠️",
    "Instant vehicle check for any %s in India 🇮🇳",
    "Don't get scammed on that second-hand %s! 💸",
    "Look at this eye-catching %s on the road! ⚡"
];

$rc_vehicles = [
    "Mahindra Thar", "Scorpio-N", "Royal Enfield Classic 350", "Toyota Fortuner",
    "Hyundai Creta", "Kia Seltos", "Tata Harrier", "Tata Nexon", "Maruti Swift",
    "KTM Duke 390", "Yamaha R15 V4", "BMW 3 Series", "Mercedes C-Class", "Audi A4",
    "Honda City", "Volkswagen Virtus", "Skoda Slavia", "Kawasaki Ninja", "Suzuki Hayabusa",
    "Honda Activa 6G", "TVS Jupiter", "Ola S1 Pro", "Ather 450X", "Tata Safari",
    "Mahindra XUV700", "Hyundai i20", "Maruti Baleno", "Toyota Innova Crysta", "MG Hector",
    "Jeep Compass", "Harley Davidson X440", "Triumph Speed 400", "Yamaha MT-15", "TVS Apache RTR",
    "Bajaj Pulsar NS200", "Royal Enfield Hunter 350", "Royal Enfield Himalayan 450", "Maruti Brezza"
];

$rc_bodies = [
    "Enter registration number to get owner name, vehicle age, RTO city & fuel type instantly! 🚗",
    "Find exact registration date, engine number, chassis number & blacklist status in 3 seconds! 🔍",
    "Check how many previous owners it had before you make an advance payment! Stay safe 🛡️",
    "Verify if the vehicle is blacklisted, financed or stolen with 1-tap RC search. Tap to check! 📱",
    "Find full RTO registration details, fitness expiry, and road tax validity right now! 🏛️",
    "Don't believe what the used car dealer claims! Verify the real RC registration facts here 💡",
    "Instant VAHAN database lookup: Owner name, insurance validity, and vehicle class in 1 tap! ⚡",
    "Check whether it is on Hypothecation / Bank loan before closing the deal! Tap here 🏦"
];

$rc_slots = ['09:00', '13:00', '17:00', '20:00'];
foreach ($rc_hooks as $hook) {
    foreach ($rc_vehicles as $veh) {
        $body = $rc_bodies[array_rand($rc_bodies)];
        $slot = $rc_slots[array_rand($rc_slots)];
        $title = sprintf($hook, $veh);
        add_draft($all_drafts, $seen_uids, $title, $body, "RC & Vehicle Search", "RC", $slot);
    }
}

// =========================================================================
// MODULE 2: TRAFFIC CHALLAN & FINES (Target: CHALLAN, ~600 items)
// =========================================================================
$challan_titles = [
    "Mama is watching! 📸👮 Any pending challan?",
    "Did you cross that yellow light yesterday? 🚦",
    "₹2,000 traffic fine without knowing? 😱",
    "Speed camera flash on your %s? ⚡",
    "Don't get stopped at the city traffic barricade! 🚨",
    "Camera spotted your %s without helmet? 🪖",
    "Pending e-Challan alert for your vehicle! 📑",
    "Court notice alert: Check unpaid challans now! ⚖️",
    "Did you park in the No-Parking zone today? 🚗🪝",
    "High-speed radar on national highway! Check fine 🛣️",
    "That traffic camera doesn't forget! Check records 👁️",
    "Avoid seizure of your %s by clearing fines! 🛵",
    "Traffic police updated pending challan list today 📋",
    "Check if any e-challan was issued to your vehicle 🔍",
    "Don't let challan penalties double up! Pay on time ⏳",
    "Wrong side driving challan caught on CCTV? 📷"
];

$challan_bodies = [
    "Check pending e-challans with photo proof, location, violation time & amount in 5 seconds! 🚨",
    "Traffic cameras in your city are active 24/7. Check if your vehicle received a fine slip! 📸",
    "Clear your pending fines online before police impound your RC book or driving licence! 💳",
    "Over-speeding, red light jumping or stop line violation? Enter vehicle number to verify! 🚦",
    "Find pending court challans and Lok Adalat settlement options right on your phone 📱",
    "Check exact photographic evidence of your traffic violation with fine breakdown now! 🔍",
    "Unpaid challans can block vehicle fitness certificate and NOC! Check and clear today 📄",
    "Avoid embarrassment on the road! Verify your vehicle's clean challan record right now ✨"
];

foreach ($challan_titles as $ctitle) {
    foreach ($rc_vehicles as $veh) {
        $title = sprintf($ctitle, $veh);
        $body = $challan_bodies[array_rand($challan_bodies)];
        $slot = $rc_slots[array_rand($rc_slots)];
        add_draft($all_drafts, $seen_uids, $title, $body, "Challan & Traffic Fines", "CHALLAN", $slot);
    }
}

// =========================================================================
// MODULE 3: RTO EXAM PREPARATION & MOCK TESTS (Target: EXAM, ~700 items)
// =========================================================================
$exam_questions = [
    "What does a flashing amber/yellow signal mean? 🚦",
    "When can you overtake a vehicle from the left? 🚗💨",
    "What is the legal blood alcohol limit while driving? 🍷🚫",
    "Who has the right of way on a steep hill climb? ⛰️",
    "What does an inverted red triangle road sign mean? 🔻",
    "What should you do when an ambulance approaches from behind? 🚑",
    "What is the maximum speed limit near schools & hospitals? 🏫",
    "When are hazard warning lights legally permitted? ⚠️",
    "What is the minimum age to apply for a transport vehicle DL? 🪪",
    "Can you reverse your vehicle on an expressway? 🛣️",
    "What does a continuous white line in the middle of road mean? 🛣️",
    "How many meters before turning must you turn on indicators? 💡",
    "What does a blue circle with red border and single slash mean? 🔵",
    "Is wearing a seatbelt compulsory for rear seat passengers? 🛡️",
    "What does a zigzag yellow line on the road indicate? 〰️",
    "What is the stopping distance on wet rain-soaked roads? 🌧️",
    "What does the hand signal of arm extended upwards mean? 🙋‍♂️",
    "What is the penalty for driving without a valid Driving Licence? ⚖️",
    "How often must a commercial vehicle undergo fitness test? 📋",
    "What is the valid duration of a Learning Licence (LL)? ⏳"
];

$exam_hooks = [
    "92%% applicants fail this RTO question! 😱 Can you answer?",
    "RTO Mock Test Question of the Day 📝 Test your score!",
    "Want to pass your RTO computer test on 1st attempt? 🎯",
    "Test your road knowledge before taking the actual RTO test! 🧠",
    "Don't get embarrassed at the RTO test center! 🙈 Practice now:",
    "Super tricky question asked in yesterday's RTO test! 🚦",
    "Only true pro drivers know the correct answer to this! 🏆",
    "Daily 1-minute RTO quiz: Can you get 5/5 correct today? 🌟"
];

$exam_bodies = [
    "A) Proceed with caution | B) Stop immediately | C) Accelerate faster. Tap to see the correct answer! 💡",
    "Practice official state RTO question bank in English & Hindi. Guaranteed 1st attempt pass! 🎓",
    "Don't pay agents ₹2,000! Learn all 250+ official RTO questions with instant explanations 📚",
    "Sharpen your traffic IQ in 60 seconds with our interactive mock exam timer! Start quiz ⏱️",
    "Know all mandatory signs, cautionary signs and road markings before your test date! 🚦",
    "Take a quick 10-question practice test now and see your passing probability score! 📊"
];

for ($i = 0; $i < count($exam_questions); $i++) {
    for ($j = 0; $j < count($exam_hooks); $j++) {
        for ($k = 0; $k < 5; $k++) {
            $title = $exam_hooks[$j] . " " . $exam_questions[$i];
            $body = $exam_bodies[array_rand($exam_bodies)];
            $slot = $rc_slots[array_rand($rc_slots)];
            add_draft($all_drafts, $seen_uids, $title, $body, "RTO Exam & Mock Tests", "EXAM", $slot);
        }
    }
}

// =========================================================================
// MODULE 4: TRAFFIC ROAD SIGNS & SYMBOLS (Target: SYMBOL, ~500 items)
// =========================================================================
$symbol_signs = [
    "No Entry ⛔", "Speed Limit 50 🛑", "Overtaking Prohibited 🚫", "Pedestrian Crossing 🚶",
    "School Ahead 🚸", "Narrow Bridge Ahead 🌉", "Steep Ascent 📈", "Slippery Road ⚠️",
    "Roundabout Ahead 🔄", "Right Hairpin Bend ↩️", "Men at Work 👷", "Height Limit 3.5m 📏",
    "No Horn / Silence Zone 🔕", "Compulsory Ahead Only ⬆️", "Give Way 🔻", "Stop 🛑",
    "No Parking 🅿️🚫", "No Standing ❌", "Falling Rocks Ahead 🪨", "Wild Animal Crossing 🦌",
    "T-Intersection Ahead 🔀", "Railway Level Crossing 🚂", "Hump or Rough Road 〰️"
];

$symbol_hooks = [
    "Do you know what this road sign means? 🛑 Don't guess and crash!",
    "Saw this symbol on the highway today? 🛣️ Here is what it means:",
    "Most drivers confuse this symbol with No Parking! ⚠️ Learn why:",
    "RTO Traffic Sign Masterclass: 💡 Test your symbol knowledge:",
    "Don't get a ₹1,000 fine for ignoring this mandatory road sign! 🚨",
    "Recognize this road sign in 3 seconds or fail your driving test! ⏱️",
    "Avoid terrible accidents! Know every cautionary highway sign 🛡️",
    "Why is this road sign shape triangular instead of circular? 📐"
];

foreach ($symbol_signs as $sign) {
    foreach ($symbol_hooks as $hook) {
        $title = $hook . " " . $sign;
        $body = "Master all 120+ mandatory, cautionary and informative traffic road symbols with high-res graphics! Tap to learn 🛑";
        $slot = $rc_slots[array_rand($rc_slots)];
        add_draft($all_drafts, $seen_uids, $title, $body, "Traffic Signs & Symbols", "SYMBOL", $slot);
        
        $title2 = "🚦 Traffic Sign Quiz: What does " . $sign . " mean?";
        $body2 = "Learn why traffic police imposes heavy penalties for violating this sign. Read meaning & rules inside 📖";
        add_draft($all_drafts, $seen_uids, $title2, $body2, "Traffic Signs & Symbols", "SYMBOL", $slot);
    }
}

// =========================================================================
// MODULE 5: TRAFFIC RULES & MV ACT PENALTIES (Target: RULES, ~500 items)
// =========================================================================
$rules_topics = [
    "Triple Riding on Bike / Scooter 🛵", "Drunk Driving & Breath Analyzer 🍷", "Driving Without Helmet 🪖",
    "Using Mobile Phone While Driving 📱", "Red Light Jumping at Crossroads 🚦", "Driving Without Seatbelt 🛡️",
    "Over-Speeding Beyond City Limit 🏎️", "Loud Aftermarket Exhaust / Silencer 🔊", "Tinted Black Glass Windows 🕶️",
    "Fancy / Modified Number Plate 🏷️", "Driving Without Valid Insurance 📄", "Driving Without Valid PUC Certificate 💨",
    "Dangerous & Rash Overtaking ⚠️", "Wrong Side Driving on Highway ⛔", "Blocking Emergency Ambulance 🚑",
    "Juvenile / Minor Driving Penalty 👶", "Overloading Commercial Goods 🚚", "Stopping on Zebra Crossing 🦓"
];

$rules_hooks = [
    "New Motor Vehicle Act Penalty Alert! 🚨",
    "Did you know this traffic rule can cost you ₹10,000? 💸",
    "Don't argue with traffic police without knowing this rule! ⚖️",
    "Updated fine list for 2026: Check the exact penalty amount 📋",
    "Avoid heavy court challans! Know your legal road rights 🛡️",
    "Can traffic police confiscate your vehicle keys? Know the law! 🔑",
    "What to do when traffic police stops you at night? 🌙 Safe tips:",
    "Is this common driving habit actually illegal in India? 😱"
];

foreach ($rules_topics as $rule) {
    foreach ($rules_hooks as $hook) {
        $title = $hook . " " . $rule;
        $body = "Check the latest MV Act fine amount, suspension period & legal sections for this violation. Stay informed! ⚖️";
        $slot = $rc_slots[array_rand($rc_slots)];
        add_draft($all_drafts, $seen_uids, $title, $body, "Traffic Rules & Penalties", "RULES", $slot);
    }
}

// =========================================================================
// MODULE 6: DAILY FUEL PRICES & SAVINGS (Target: FUEL, ~450 items)
// =========================================================================
$cities = [
    "Delhi", "Mumbai", "Bangalore", "Hyderabad", "Chennai", "Kolkata", "Ahmedabad",
    "Pune", "Surat", "Jaipur", "Lucknow", "Chandigarh", "Indore", "Bhopal", "Nagpur",
    "Patna", "Vadodara", "Rajkot", "Coimbatore", "Kochi", "Visakhapatnam", "Guwahati"
];

$fuel_hooks = [
    "Petrol & Diesel price updated today in %s! ⛽",
    "Fuel rate dropped or increased in %s? Check now 📉📈",
    "Planning to fill tank in %s today? Check prices first 🚗",
    "Save ₹200+ on your full tank in %s! Today's live rates 💰",
    "CNG and Petrol price comparison for %s today 🌿",
    "Daily fuel alert: Did fuel prices change in %s? ⛽"
];

foreach ($cities as $city) {
    foreach ($fuel_hooks as $fhook) {
        $title = sprintf($fhook, $city);
        $body = "Track live daily Petrol, Diesel & Auto-CNG prices with 30-day historical trend charts in your city! Tap to check ⛽";
        $slot = '09:00';
        add_draft($all_drafts, $seen_uids, $title, $body, "Fuel Prices & Savings", "FUEL", $slot);
    }
}

// =========================================================================
// MODULE 7: VEHICLE RESALE VALUE ESTIMATOR (Target: RESALE, ~450 items)
// =========================================================================
$resale_cars = [
    "Maruti Swift (2018)", "Hyundai Creta (2020)", "Tata Nexon (2021)", "Honda City (2019)",
    "Mahindra Thar (2022)", "Toyota Fortuner (2019)", "Kia Seltos (2021)", "Maruti Baleno (2017)",
    "Royal Enfield Classic (2019)", "KTM Duke 200 (2020)", "Honda Activa 5G (2018)", "Yamaha R15 (2021)"
];

$resale_hooks = [
    "What is your %s worth in the market today? 🏷️",
    "Thinking of selling your %s? Don't accept lowball offers! 💸",
    "Instant used valuation: Check market price for %s 📈",
    "Get true dealer resale price for your %s in 10 seconds 🔍",
    "How much has your %s depreciated this year? 📉",
    "Upgrading your ride? Calculate true exchange value for %s 🚗"
];

foreach ($resale_cars as $car) {
    foreach ($resale_hooks as $rhook) {
        $title = sprintf($rhook, $car);
        $body = "Our AI algorithm estimates exact market resale value based on make, model year, condition & mileage. Check now! 💰";
        $slot = '13:00';
        add_draft($all_drafts, $seen_uids, $title, $body, "Vehicle Resale Value", "RESALE", $slot);
    }
}

// =========================================================================
// MODULE 8: MILEAGE & TRIP COST CALCULATOR (Target: MILEAGE, ~400 items)
// =========================================================================
$trip_destinations = [
    "Goa", "Manali", "Ladakh", "Udaipur", "Rishikesh", "Lonavala", "Mount Abu",
    "Ooty", "Coorg", "Jaipur", "Pondicherry", "Shimla", "Diu", "Mahabaleshwar"
];

$trip_hooks = [
    "Road trip to %s planned? Calculate fuel cost per person! 🏖️",
    "How much petrol will your car drink on the way to %s? ⛽",
    "Splitting fuel bills for %s trip? Use smart trip calculator! 🧮",
    "Is your car giving bad mileage on highway to %s? Check true km/L 🚗",
    "Budget your road expedition to %s before hitting the highway! 🛣️"
];

foreach ($trip_destinations as $dest) {
    foreach ($trip_hooks as $thook) {
        $title = sprintf($thook, $dest);
        $body = "Calculate exact total fuel required, cost per km, and divide expenses fairly between friends with 1 tap! 🚗⛽";
        $slot = '17:00';
        add_draft($all_drafts, $seen_uids, $title, $body, "Mileage & Trip Calculator", "MILEAGE", $slot);
    }
}

// =========================================================================
// MODULE 9: CAR & BIKE LOAN EMI CALCULATOR (Target: LOAN, ~400 items)
// =========================================================================
$loan_vehicles = [
    "Mahindra Scorpio-N", "Royal Enfield Hunter 350", "Toyota Fortuner", "Hyundai Creta",
    "Tata Harrier EV", "KTM Duke 390", "BMW G310R", "Kawasaki Ninja 300", "Kia Seltos",
    "Mahindra Thar Roxx", "Tata Nexon EV", "Maruti Grand Vitara", "Triumph Speed 400"
];

$loan_hooks = [
    "Dreaming of owning a %s? Calculate monthly EMI! 💳",
    "How much salary do you need to afford a %s? 📊",
    "Zero down payment trap vs Smart EMI for %s! 💡",
    "Calculate exact interest & monthly installment for %s 🧮",
    "Save ₹50,000 on car loan interest before buying a %s! 🏦"
];

foreach ($loan_vehicles as $lveh) {
    foreach ($loan_hooks as $lhook) {
        $title = sprintf($lhook, $lveh);
        $body = "Calculate monthly EMI, total interest payable, and loan amortization schedule in 3 seconds! Tap to compute 💳";
        $slot = '20:00';
        add_draft($all_drafts, $seen_uids, $title, $body, "Car & Bike Loan EMI", "LOAN", $slot);
    }
}

// =========================================================================
// MODULE 10: DOCUMENT VAULT & EXPIRY REMINDERS (Target: DOCUMENT, ~400 items)
// =========================================================================
$doc_titles = [
    "Is your vehicle PUC expired? ₹10,000 fine alert! 😱",
    "Driving with lapsed insurance? Playing with fire! 🛡️",
    "Traffic police check ahead! Are your RC documents digital? 📱",
    "Never pay fines for forgotten documents! Set expiry reminder ⏰",
    "Is your fitness certificate due for renewal this month? 📋",
    "Keep RC, DL, Insurance & PUC safely in your pocket vault 🗄️",
    "Expired PUC fine is now ₹10,000 under new MV Act! Check date 💨",
    "Did your bike insurance expire yesterday without notice? 🚨",
    "Save your vehicle documents offline for instant traffic checks 📄",
    "Commercial vehicle fitness expiry reminder: Check validity 🚚"
];

$doc_bodies = [
    "Set automated alerts before your Insurance, PUC & Fitness expire! Avoid spot penalties and legal troubles 🚨",
    "Store verified digital copies of RC, DL, Pollution Certificate and Insurance policy in 1 tap! 🛡️",
    "Traffic police accepts DigiLocker and mParivahan valid documents. Keep your reminders active today! 📱",
    "Don't wait for a ₹10,000 challan at the traffic signal! Check your vehicle PUC expiry date right now 💨"
];

foreach ($doc_titles as $dtitle) {
    foreach ($rc_vehicles as $veh) {
        $title = str_replace("vehicle", $veh, $dtitle);
        $body = $doc_bodies[array_rand($doc_bodies)];
        $slot = $rc_slots[array_rand($rc_slots)];
        add_draft($all_drafts, $seen_uids, $title, $body, "Document & Expiry Reminders", "DOCUMENT", $slot);
    }
}

// =========================================================================
// MODULE 11: CELEBRITY & VIP VEHICLES (Target: CELEBRITY, ~300 items)
// =========================================================================
$celebs = [
    "Virat Kohli", "MS Dhoni", "Rohit Sharma", "Shah Rukh Khan", "Salman Khan",
    "Amitabh Bachchan", "Mukesh Ambani", "Ratan Tata", "Cristiano Ronaldo", "Ranveer Singh"
];

$celeb_hooks = [
    "Which beast car does %s drive? Peek into garage! 🏎️",
    "Look at the insane VIP number plate of %s's ride! 🌟",
    "Supercars owned by %s: Full collection revealed! 🚗💨",
    "How many crores is %s's luxury car worth? Check specs! 💎"
];

foreach ($celebs as $cel) {
    foreach ($celeb_hooks as $chook) {
        $title = sprintf($chook, $cel);
        $body = "Check out complete vehicle specs, registration number, horsepower and stunning photos of celebrity rides! 🌟🏎️";
        $slot = '20:00';
        add_draft($all_drafts, $seen_uids, $title, $body, "Celebrity & VIP Vehicles", "CELEBRITY", $slot);
    }
}

// =========================================================================
// MODULE 12: RTO OFFICES & OFFICIAL FORMS (Target: OFFICE, ~300 items)
// =========================================================================
$states = [
    "GJ (Gujarat)", "MH (Maharashtra)", "DL (Delhi)", "UP (Uttar Pradesh)", "RJ (Rajasthan)",
    "KA (Karnataka)", "TN (Tamil Nadu)", "MP (Madhya Pradesh)", "PB (Punjab)", "HR (Haryana)"
];

$form_types = [
    "Form 28 (No Objection Certificate / NOC)",
    "Form 29 (Notice of Vehicle Ownership Transfer)",
    "Form 30 (Application for Transfer of Ownership)",
    "Form 33 (Change of Address in RC Book)",
    "Form 20 (Application for New Vehicle Registration)"
];

foreach ($states as $st) {
    foreach ($form_types as $form) {
        $title = "Selling or moving vehicle in %s? Download %s! 📄";
        $title = sprintf($title, $st, $form);
        $body = "Download official RTO PDF forms directly and avoid paying ₹1,500 to RTO agents! Free download inside 🏛️";
        $slot = '13:00';
        add_draft($all_drafts, $seen_uids, $title, $body, "RTO Offices & Forms", "OFFICE", $slot);
    }
}

// =========================================================================
// MODULE 13: HILARIOUS DRIVING MEMES & RELATABLE ROAD HYPE (~500 items)
// =========================================================================
$funny_hooks = [
    ["😂 When traffic police signals you to stop:", "Heart rate: 180 BPM! Even when you have all documents valid! Check challans now 🫀"],
    ["🛵 Indicator left, turn right:", "Classic aunty on Activa moment! Stay alert and learn correct road signals today 🛑"],
    ["☕ Chai peene chale? But wait:", "Check your bike petrol and tire pressure before setting out with the squad 🏍️"],
    ["🕶️ Wearing sunglasses inside car = +50 HP:", "Looking cool is great, but don't forget to wear your seatbelt too! Stay safe 🛡️"],
    ["🤦‍♂️ When friend says 'Bhai race lagate hain':", "Remind them that hospital bills are more expensive than superbike EMI! 🏥"],
    ["⚡ High beam drivers on single road:", "Dear drivers, that lever goes down too! Don't blind oncoming traffic! 💡🚫"],
    ["🏆 You parked in 1 smooth reverse attempt?", "Take a bow! You are officially an RTO driving legend today 🚗✨"],
    ["💸 That feeling when you pass through green light:", "Saved 90 seconds and ₹2,000 signal jumping challan! Victory dance 🕺"],
    ["🍕 Pizza delivery guy overtaking your sports bike:", "He has nothing to lose, you have EMI to pay! Respect the hustle 💨"],
    ["🪞 Mirrors are not just for fixing your hair!", "Check blind spots before changing lanes on the expressway today 🚗🔍"],
    ["🚗 Weekend car wash ritual begins!", "A clean car drives smoother (well, mentally at least!). Keep rolling ✨"],
    ["🚨 That one friend who never wears helmet:", "Show them the ₹1,000 fine receipt before giving them a lift! 🪖"],
    ["🛣️ NH48 highway calling your name?", "Check engine oil, tire pressure, and fastag balance before you hit the road ⛽"],
    ["🛑 Yellow light means SLOW DOWN:", "Not 'accelerate like you are in Fast & Furious'! Drive safe today 🏎️💨"],
    ["🚙 Fortuner horn from 500 meters behind:", "Move aside, royalty has arrived! Check vehicle details in 1 tap 👑"],
    ["🤫 Secret hack to avoid challans forever:", "Wear helmet, carry PUC, follow signals, and practice daily RTO quiz! 🧠"]
];

foreach ($funny_hooks as $meme) {
    foreach ($rc_vehicles as $veh) {
        $title = str_replace("car", $veh, $meme[0]);
        $body = $meme[1];
        $slot = $rc_slots[array_rand($rc_slots)];
        add_draft($all_drafts, $seen_uids, $title, $body, "Funny Road Hype & Memes", "RULES", $slot);
    }
}

// =========================================================================
// MODULE 14: DRIVING LICENCE (DL) SEARCH & STATUS (Target: DL, ~600 items)
// =========================================================================
$dl_hooks = [
    "Is your Driving Licence expired or active? 🪪",
    "Forgot your Driving Licence number? Search here! 🔍",
    "Did you know DL validity expires at age 40? 📅",
    "Verify any driver's licence details in 3 seconds ⚡",
    "Lost physical DL? Download digital copy instantly 📱",
    "Check your DL test status & dispatch tracking 📮",
    "Is your DL valid for commercial driving? Check badge 🚚",
    "International Driving Permit (IDP) validity check ✈️",
    "Address changed? Update your DL before penalty ⚖️",
    "Don't carry fake DL! Verify Sarathi database records 🏛️",
    "Learning Licence expiring this week? Apply for permanent DL ⏳",
    "Suspended or active? Check DL status on Sarathi portal 🚨",
    "Check DL class endorsement for motorcycle & LMV 🏍️🚗",
    "How to renew your expired Driving Licence online? 📄",
    "Police verification of Driver Licence made easy 👮"
];

$dl_scenarios = [
    "Enter DL number & Date of Birth to fetch official Parivahan records, validity period & authorized vehicle classes! 🪪",
    "Avoid ₹5,000 fine for driving with an expired or unauthorized licence. Verify current status in 1 tap! 🚨",
    "Check whether your driving licence is linked with Aadhaar and mobile number for instant paperless renewal 📱",
    "Track your printed smart card DL delivery speed and RTO dispatch consignment status right now! 📮",
    "Know the grace period for DL renewal and avoid paying double late fees at the RTO! Read full rules ⚖️",
    "Verify your blood group, organ donor status and emergency contact details recorded on your DL card 🩸"
];

foreach ($dl_hooks as $dl_hook) {
    foreach ($rc_vehicles as $veh) {
        $title = $dl_hook . " (" . $veh . " owner)";
        $body = $dl_scenarios[array_rand($dl_scenarios)];
        $slot = $rc_slots[array_rand($rc_slots)];
        add_draft($all_drafts, $seen_uids, $title, $body, "Driving Licence", "DL", $slot);
    }
}

// =========================================================================
// MODULE 15: CITY-SPECIFIC SPEED RADAR & CCTV ALERTS (Target: CHALLAN, ~600 items)
// =========================================================================
$radar_cities = [
    "Ahmedabad (SG Highway)", "Surat (Dumas Road)", "Vadodara (OP Road)", "Rajkot (Kalawad Road)",
    "Mumbai (Sea Link & Eastern Freeway)", "Pune (Expressway)", "Delhi (Ring Road)", "Gurugram (Golf Course)",
    "Noida (Expressway)", "Bengaluru (Outer Ring Road)", "Hyderabad (ORR)", "Chennai (OMR)",
    "Kolkata (EM Bypass)", "Jaipur (JLN Marg)", "Lucknow (Shaheed Path)", "Chandigarh (Madhya Marg)"
];

$radar_hooks = [
    "Speed radar cameras active on %s today! 📸🚨",
    "Red light jump caught by high-res AI camera in %s? 🚦",
    "No-parking towing van patrolling near %s! 🚗🪝",
    "Seatbelt & mobile detection camera alert in %s 📱",
    "Did you get an automated challan message in %s? 💸",
    "Traffic police barricade checking documents in %s! 🛑",
    "High-speed interceptor laser guns deployed in %s 🏎️",
    "Avoid ₹2,000 fine! Check CCTV challan records in %s 📋"
];

foreach ($radar_cities as $rcity) {
    foreach ($radar_hooks as $rhook) {
        $title = sprintf($rhook, $rcity);
        $body = "Check live e-challan status with photographic CCTV proof, time, exact location & payment link in 5 seconds! 🚨";
        $slot = $rc_slots[array_rand($rc_slots)];
        add_draft($all_drafts, $seen_uids, $title, $body, "Challan & Traffic Fines", "CHALLAN", $slot);
    }
}

// =========================================================================
// MODULE 16: VEHICLE HEALTH & MAINTENANCE HACKS (Target: MILEAGE, ~600 items)
// =========================================================================
$maintenance_topics = [
    "Tire pressure drops 2 PSI in winter! Check %s mileage ❄️",
    "Black smoke coming from %s exhaust? Check engine health 💨",
    "Spongy brake pedal in your %s? Bleed brake fluid now! 🛑",
    "Is your %s engine oil honey-colored or tar-black? 🛢️",
    "Clutch slipping in your %s? Save ₹15,000 replacement bill! ⚙️",
    "Coolant leak alert: Don't let your %s engine overheat! 🌡️",
    "Tire tread depth coin test for your %s before rains 🌧️",
    "Car AC not cooling your %s? Quick gas & cabin filter fix ❄️",
    "Battery terminal corrosion on %s? Easy 2-minute cleaning hack 🔋",
    "Vibration at 80 km/h in your %s? Wheel alignment needed! ⚖️",
    "Don't turn off %s turbo engine immediately after highway drive! ⏳",
    "Water inside %s headlights? Prevent electrical short circuits 💡",
    "Suspension noise over speed breakers in your %s? Check bushings 🔩",
    "Spark plug fouling symptoms in your %s: Restore lost pickup ⚡",
    "Rat bite repellent hack for your %s wiring harness! 🐀🚫"
];

$maintenance_bodies = [
    "Simple 2-minute weekly check saves you ₹20,000+ in unexpected garage repair bills! Read step-by-step hack 🔧",
    "Calculate how much extra fuel a bad sensor or low tire pressure is wasting every single month! 🧮⛽",
    "Pro driver maintenance tips: Extend your vehicle engine life beyond 2,00,000 km effortlessly! 🚗✨",
    "Check our smart vehicle maintenance checklist before planning long highway road trips 🛣️"
];

foreach ($maintenance_topics as $mtopic) {
    foreach ($rc_vehicles as $veh) {
        $title = sprintf($mtopic, $veh);
        $body = $maintenance_bodies[array_rand($maintenance_bodies)];
        $slot = $rc_slots[array_rand($rc_slots)];
        add_draft($all_drafts, $seen_uids, $title, $body, "Mileage & Trip Calculator", "MILEAGE", $slot);
    }
}

// =========================================================================
// MODULE 17: EV VS PETROL, HYBRID & GREEN ENERGY (Target: FUEL, ~500 items)
// =========================================================================
$ev_topics = [
    "Electric Scooter vs Honda Activa: Which saves more ₹₹? 🛵",
    "Tata Nexon EV vs Creta Petrol: 5-year running cost math 🚗",
    "How much does it cost to charge an EV at home in India? ⚡",
    "Does fast DC charging ruin EV battery health? Truth revealed 🔋",
    "Government EV subsidies and road tax exemption in your state 🏛️",
    "Solar rooftop charging for your electric vehicle: Zero fuel bills ☀️",
    "Hybrid car or Pure EV? Best choice for Indian city traffic 🚦",
    "Real-world highway range of electric cars in Indian summer 🌡️",
    "Battery replacement cost after 8 years: Myth vs Reality 💡",
    "CNG vs Electric: Which gives the cheapest cost per km? 🌿"
];

$ev_bodies = [
    "Run the exact comparison on our Fuel & EV running cost calculator before you buy your next ride! 🧮",
    "Calculate your monthly savings: Average commuter saves ₹3,500 every month on electricity vs petrol! 💰",
    "Check live state-wise EV subsidy benefits, green number plate perks and toll discounts today 🌿"
];

foreach ($ev_topics as $etop) {
    foreach ($cities as $city) {
        $title = $etop . " (" . $city . " edition)";
        $body = $ev_bodies[array_rand($ev_bodies)];
        $slot = '13:00';
        add_draft($all_drafts, $seen_uids, $title, $body, "Fuel Prices & Savings", "FUEL", $slot);
    }
}

// =========================================================================
// MODULE 18: RELATABLE ROAD MEMES & VIRAL HYPE BATCH 2 (~600 items)
// =========================================================================
$meme2_hooks = [
    ["🛣️ Indian road rule #1: Pothole slalom champion!", "Navigating city roads feels like playing a video game! Keep your suspension safe 🕹️"],
    ["🔊 Honking 0.0001 seconds after signal turns green:", "Where are you rushing to, bro? The moon? Relax and drive peacefully 🚦🧘‍♂️"],
    ["🌧️ Turning on hazard lights in heavy rain:", "Hazard lights are for emergencies, not monsoon cruising! Use fog lights instead 💡"],
    ["🛵 Riding with rear-view mirrors folded inward:", "Aerodynamics +1, Safety -100! Unfold your mirrors and watch your blind spot 👀"],
    ["👨‍👦 When dad sits beside you while you drive:", "Tension higher than India vs Pakistan World Cup final! Drive smooth today 🏆"],
    ["🪟 Roll down window to toss trash on highway?", "Real drivers keep a trash bag in their car. Keep our roads clean & green 🇮🇳🌱"],
    ["💨 High speed flyover descent thrill:", "Feels amazing, but watch that speed radar at the end of the ramp! 📸💸"],
    ["🛑 Parking so close that the other driver can't enter:", "Don't be that guy! Leave space for human beings to open their doors 🚗🚪"],
    ["👀 That moment when you hear a new squeak in your car:", "Turn off music, listen intensely, and wonder if your wallet is in danger! 🎶🔧"],
    ["🕶️ Washing your car on Sunday:", "Forecast: 100% chance of rain immediately after! Murphy's Law of car wash 🌧️😂"]
];

foreach ($meme2_hooks as $meme2) {
    foreach ($rc_vehicles as $veh) {
        $title = str_replace("car", $veh, $meme2[0]);
        $body = $meme2[1];
        $slot = $rc_slots[array_rand($rc_slots)];
        add_draft($all_drafts, $seen_uids, $title, $body, "Funny Road Hype & Memes", "RULES", $slot);
    }
}

// =========================================================================
// MODULE 19: FASTAG, TOLL RULES & HIGHWAY PERMITS (Target: RULES, ~300 items)
// =========================================================================
$fastag_hooks = [
    "FASTag blacklisted or low balance? Don't pay double toll! 💳",
    "New FASTag KYC rule deadline alert for your %s! ⚠️",
    "Did you get charged double toll cash penalty on NH toll? 💸",
    "How to transfer FASTag when selling your %s? 🚗",
    "One Vehicle One FASTag policy: Check active tags for %s 🏷️",
    "Toll exemption & monthly local pass rules for %s owners 🛣️",
    "FASTag scanned twice at toll plaza? How to get refund! 💰",
    "NHAI annual pass & fast lane etiquette for highway drivers 🚦"
];

foreach ($fastag_hooks as $fhook) {
    foreach ($rc_vehicles as $veh) {
        $title = sprintf($fhook, $veh);
        $body = "Check active FASTag status, issuing bank, linked vehicle number & balance alert before entering highway toll plaza! 🛣️💳";
        $slot = $rc_slots[array_rand($rc_slots)];
        add_draft($all_drafts, $seen_uids, $title, $body, "Traffic Rules & Penalties", "RULES", $slot);
    }
}

// =========================================================================
// MODULE 20: RTO DISTRICT NUMBER PLATE CODE QUIZ (Target: EXAM, ~400 items)
// =========================================================================
$rto_district_codes = [
    "GJ-01" => "Ahmedabad", "GJ-02" => "Mehsana", "GJ-03" => "Rajkot", "GJ-04" => "Bhavnagar",
    "GJ-05" => "Surat", "GJ-06" => "Vadodara", "GJ-07" => "Nadiad", "GJ-10" => "Jamnagar",
    "GJ-27" => "Ahmedabad East", "MH-01" => "Mumbai South", "MH-02" => "Mumbai West", "MH-03" => "Mumbai East",
    "MH-04" => "Thane", "MH-12" => "Pune", "MH-14" => "Pimpri Chinchwad", "MH-15" => "Nashik",
    "MH-31" => "Nagpur", "DL-01" => "Delhi North", "DL-03" => "Delhi South", "DL-04" => "Delhi West",
    "KA-01" => "Bangalore Central", "KA-03" => "Bangalore East", "KA-05" => "Bangalore South",
    "RJ-14" => "Jaipur", "UP-32" => "Lucknow", "UP-16" => "Noida", "UP-14" => "Ghaziabad",
    "TS-09" => "Hyderabad Central", "TN-01" => "Chennai Central", "WB-02" => "Kolkata Central"
];

$code_hooks = [
    "Which Indian city does number plate %s belong to? 🚗🔍",
    "Spot a %s vehicle on the road? Guess the RTO district! 🧠",
    "RTO District Quiz: Can you name the RTO office for %s? 🏛️",
    "Do you know your state RTO codes? Where is %s from? 🇮🇳"
];

foreach ($rto_district_codes as $code => $city) {
    foreach ($code_hooks as $chook) {
        $title = sprintf($chook, $code);
        $body = "Correct answer: " . $city . " RTO! Test your knowledge on 500+ Indian RTO district codes & office locations inside 🗺️";
        $slot = $rc_slots[array_rand($rc_slots)];
        add_draft($all_drafts, $seen_uids, $title, $body, "RTO Offices & Forms", "EXAM", $slot);
    }
}

echo "Total generated drafts in memory: " . count($all_drafts) . "\n";

// Batch insert into database in chunks of 500
$chunk_size = 500;
$total = count($all_drafts);
$inserted = 0;

$stmt = $db->prepare("INSERT IGNORE INTO `rto_push_notification_bank` 
    (`content_uid`, `title`, `body`, `category_name`, `language`, `target_screen`, `status`, `scheduled_slot`) 
    VALUES (?, ?, ?, ?, 'en', ?, 'DRAFT', ?)");

for ($i = 0; $i < $total; $i += $chunk_size) {
    $chunk = array_slice($all_drafts, $i, $chunk_size);
    $db->begin_transaction();
    foreach ($chunk as $item) {
        $stmt->bind_param('ssssss', 
            $item['content_uid'],
            $item['title'],
            $item['body'],
            $item['category_name'],
            $item['target_screen'],
            $item['scheduled_slot']
        );
        $stmt->execute();
        $inserted += $stmt->affected_rows;
    }
    $db->commit();
    echo "Progress: " . min($i + $chunk_size, $total) . " / $total processed ($inserted new inserted)\n";
}

$stmt->close();

// Also approve the first 300 drafts immediately so scheduled slots have ready-to-dispatch items!
$db->query("UPDATE `rto_push_notification_bank` SET `status` = 'APPROVED' WHERE `status` = 'DRAFT' LIMIT 300");

// Check current stats in database
$count_res = $db->query("SELECT status, count(*) as count FROM `rto_push_notification_bank` GROUP BY status");
echo "\n================ DATABASE SUMMARY ================\n";
while ($row = $count_res->fetch_assoc()) {
    echo "Status: " . $row['status'] . " => " . $row['count'] . " items\n";
}

$total_res = $db->query("SELECT count(*) as total FROM `rto_push_notification_bank`")->fetch_assoc();
echo "TOTAL RTO NOTIFICATIONS IN BANK: " . $total_res['total'] . "\n";
echo "==================================================\n";

// Export JSON backup for offline and documentation use
$export_path = __DIR__ . '/generated_5000_rto_bank.json';
file_put_contents($export_path, json_encode($all_drafts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Exported full JSON bank to: " . $export_path . "\n";

$db->close();
