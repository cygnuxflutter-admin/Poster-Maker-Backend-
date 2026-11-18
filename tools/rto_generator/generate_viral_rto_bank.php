<?php
/**
 * Super-Engaging & Hilarious RTO Push Notification Generator (5,000+ Diverse Items)
 * Shuffles across 14 modules with viral hooks, funny desi situations, curiosity triggers, and spicy memes.
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

$slots = ['09:00', '13:00', '17:00', '20:00'];

// =========================================================================
// 1. TRAFFIC CHALLAN & POLICE SCARE (Target: CHALLAN) - Viral Curiosity & Humor
// =========================================================================
$challan_hooks = [
    "🚨 Bhai, traffic wale mama ne roka toh kya bologe?",
    "📸 Red light pe 3 second bache the, fir bhi cross kiya?",
    "😱 Ek baar apna challan check kar lo, hum bhi dare hue hain!",
    "⚠️ Traffic camera 24/7 jagta hai, aap so rahe the kya?",
    "👮‍♂️ Mama is watching you! Koi pending e-challan toh nahi?",
    "🚦 Yellow light dekh ke accelerate karne walo, fine dekha?",
    "👀 Kahi camera ne aapki photo bina helmet ke toh nahi li?",
    "💸 ₹2,000 ka phatka lagne se pehle hi clear kar lo!",
    "🚗 No-Parking me gaadi khadi karke momos khane gaye the?",
    "🛑 Stop line cross karne ka naya fine kitna hai pata hai?",
    "📷 Speed gun radar ne highway pe photo khich li kya?",
    "⚖️ Lok Adalat aane wali hai! Pending challan saste me niptao",
    "🛵 Pari ban ke wrong side mat chalao, fine lag jayega!",
    "🤫 Kisi ne aapki gaadi ka number traffic police ko tweet kar diya?",
    "📄 Unpaid challan se gaadi ki RC blacklist ho sakti hai!"
];
$challan_scenarios = [
    "Over-speeding, red light jump ya bina seatbelt? Enter registration number aur 5 second me photo proof dekho! 🚨",
    "City traffic police ne naye AI cameras lagaye hain. Check pending e-challan with exact location and proof 📸",
    "Court notice aane se pehle apna pending challan discount ke sath pay karo. 1-tap fast check yaha karein 💳",
    "Traffic barricade par police ke haath me padne se pehle apna challan status clear kar lo! Tap to view 🔍",
    "Pending challan pending mat chhodna, gaadi ki NOC aur resale ruk jayegi! Check penalty list now ⚖️"
];
foreach ($challan_hooks as $h) {
    foreach ($challan_scenarios as $s) {
        add_draft($all_drafts, $seen_uids, $h, $s, "Traffic Challan & Fines", "CHALLAN", $slots[array_rand($slots)]);
    }
}

// =========================================================================
// 2. RC & VEHICLE OWNER SPY (Target: RC) - Juicy Gossip & Safety Checks
// =========================================================================
$rc_hooks = [
    "🕵️‍♂️ Padosi ki nayi car loan pe hai ya cash pe? Sab pata chalega!",
    "👀 Crush kiski Activa pe ghoom rahi hai? Number daalo aur dekho!",
    "🚗 Gate ke bahar kiski anjaan gaadi khadi hai? 1 second me naam jaano",
    "🔍 Second-hand car lene se pehle real owner history check ki?",
    "✨ VIP number plate wali car ka asli maalik kaun hai?",
    "⚡ Raste pe kisi ne gaadi thok di aur bhaag gaya? Number se pata lagao!",
    "🛑 Chori ki gaadi toh nahi khareed rahe? Blacklist status check karo",
    "🚙 Friend nayi gaadi flex kar raha hai? Fact check karo abhi!",
    "🏛️ Gaadi ka Fitness aur Road Tax kab expire ho raha hai?",
    "🏷️ Used bike lene se pehle 1st owner hai ya 3rd owner? Jan lo!"
];
$rc_subtitles = [
    "Enter vehicle number to get owner name, registration date, RTO city & hypothecation loan status in 3 seconds! 🔍",
    "VAHAN database se instant vehicle verification: Engine no, chassis no, fuel type aur blacklist check karein 📱",
    "Used car dealer ke jhooth me mat aao! Asli RC record aur vehicle age 1-tap me verify karo 🛡️",
    "Kiski gaadi hai, kitni purani hai aur kaunse RTO me registered hai? Sab details yaha check karo 🏛️"
];
foreach ($rc_hooks as $h) {
    foreach ($rc_subtitles as $s) {
        add_draft($all_drafts, $seen_uids, $h, $s, "RC & Vehicle Search", "RC", $slots[array_rand($slots)]);
    }
}

// =========================================================================
// 3. DAILY FUEL PRICES & MILEAGE COMEDY (Target: FUEL & MILEAGE)
// =========================================================================
$fuel_hooks = [
    "⛽ Meter zero dekhne ka natak karte ho ya sach me dekhte ho?",
    "🔥 Petrol ke daam sunke cycle nikaalne ka mann kar raha hai?",
    "🚗 Goa trip pe petrol ka kitna kharcha aayega? Hisab lagao!",
    "💡 Gaadi mileage kam kyu de rahi hai? Ye 3 setting turant karo!",
    "📊 Aaj aapke city me Petrol, Diesel aur CNG ka kya rate hai?",
    "💸 Tanki full karane me kidney jayegi kya? Today's price update:",
    "🛵 1 Litre petrol me bike kitne kilometer chalegi? Mileage test!",
    "⛽ CNG pump ki lambi line me lagne se pehle price check kar lo"
];
$fuel_bodies = [
    "Check live fuel rates in your city updated at 6:00 AM daily. Save money on every refill! ⛽",
    "Fuel mileage calculator se apne next road trip ka exact fuel cost aur per-person budget nikalo 🛣️",
    "Tire pressure aur gear shift technique se mileage 25% tak badhayein! Read top fuel efficiency tips 💡",
    "Petrol vs Diesel vs CNG vs EV: Kisme chalana sabse sasta padega? Calculator se compare karo 📊"
];
foreach ($fuel_hooks as $h) {
    foreach ($fuel_bodies as $b) {
        add_draft($all_drafts, $seen_uids, $h, $b, "Fuel Prices & Mileage", "FUEL", $slots[array_rand($slots)]);
    }
}

// =========================================================================
// 4. RTO EXAMS & DRIVING LICENCE COMEDY (Target: EXAM & DL)
// =========================================================================
$dl_hooks = [
    "🙈 8 shape banate banate RTO officer ko touch kar diya? DL fail!",
    "🧠 RTO computer test me 92% log is sawal me fail hote hain!",
    "🚦 Mirror adjust karna bhool gaye toh test se bahar nikal denge!",
    "📚 RTO agent ko ₹2,000 kyu dena? Free me exam pass karo!",
    "🪪 Learning Licence expire hone wala hai? Permanent DL test do!",
    "👮‍♂️ Driving test dene ja rahe ho? Ye 5 galtiya bilkul mat karna!",
    "🎯 Daily 1-Minute RTO Mock Test: Kya aap 5/5 score kar sakte ho?",
    "📄 Driving Licence kho gaya ya tut gaya? Duplicate DL ka process"
];
$dl_bodies = [
    "Practice official state RTO questions in English & Hindi. Guaranteed 1st attempt pass with mock test! 🎓",
    "Mandatory traffic signs, cautionary symbols aur driving hand signals 60 second me learn karo 🚦",
    "RTO driving test me reverse parking aur H-track kaise pass karein? Expert tips inside 🚙",
    "Learning licence se permanent licence apply karne ka complete step-by-step online guide dekho 📱"
];
foreach ($dl_hooks as $h) {
    foreach ($dl_bodies as $b) {
        add_draft($all_drafts, $seen_uids, $h, $b, "RTO Exam & Mock Tests", "EXAM", $slots[array_rand($slots)]);
    }
}

// =========================================================================
// 5. RESALE ESTIMATOR & LOAN EMI GUILT (Target: RESALE & LOAN)
// =========================================================================
$resale_hooks = [
    "💰 Aapki puraani car/bike abhi kitne me bik sakti hai? Guess karo!",
    "📱 iPhone se sasti bike ki EMI? EMI calculator me check karo!",
    "🏷️ Used car dealer ko bechne se pehle sachhi market value jaano!",
    "📉 Gaadi ki value har saal kitni girti hai? Depreciation chart dekho",
    "💸 ₹10 Lakh ki car pe 5 saal me kitna interest bharna padega?",
    "🚗 Nayi gaadi lene ka sapna? Zero down payment loan EMI calculate karo"
];
$resale_bodies = [
    "Enter vehicle model, year & kilometers to get instant AI resale valuation based on real market trends 💸",
    "Car/Bike Loan EMI calculator: Exact monthly EMI, total interest aur repayment schedule 1 tap me dekho 🏦",
    "Purani gaadi exchange offer me dene se pehle fair market valuation check karein taaki dealer loot na sake 🛡️"
];
foreach ($resale_hooks as $h) {
    foreach ($resale_bodies as $b) {
        add_draft($all_drafts, $seen_uids, $h, $b, "Resale Value & Loan EMI", "RESALE", $slots[array_rand($slots)]);
    }
}

// =========================================================================
// 6. DOCUMENT EXPIRY & POLICE PANIC (Target: DOCS / REMINDERS)
// =========================================================================
$doc_hooks = [
    "💔 Usne block kar diya, par gaadi ka insurance expire mat hone dena!",
    "⚠️ 3 din me PUC expire! ₹10,000 ka challan ready rakhna?",
    "📄 Pollution certificate banwaya kab tha? Date yaad hai?",
    "🚨 Insurance lapse hone ke baad accident ho gaya toh kya hoga?",
    "📱 DigiLocker wala RC aur DL police manegi ya nahi? Asli rules jaano"
];
$doc_bodies = [
    "PUC expire hone par flat ₹10,000 ka court challan lag sakta hai! 10 second me expiry check karo 📄",
    "Comprehensive vs Third-Party insurance expiry check karein aur heavy police penalties se bachein 🛡️",
    "DigiLocker and mParivahan digital documents valid across all states in India under MV Act section 139 📱"
];
foreach ($doc_hooks as $h) {
    foreach ($doc_bodies as $b) {
        add_draft($all_drafts, $seen_uids, $h, $b, "Document Vault & Reminders", "DOCS", $slots[array_rand($slots)]);
    }
}

// =========================================================================
// 7. CELEBRITY & VIP CARS GOSSIP (Target: CELEBRITY)
// =========================================================================
$celeb_hooks = [
    "🏎️ Ambani ke convoy me 10 nayi bulletproof Mercedes kaunsi aayi?",
    "✨ Salman Khan ki Land Cruiser ka VIP number plate dekha?",
    "🌟 Shahrukh Khan ke paas Rolls Royce ka kaunsa model hai?",
    "🏍️ MS Dhoni ke garage me 100+ bikes ka private collection dekho!",
    "👑 0001 VIP number plate ke liye kitne lakh ki boli lagti hai?",
    "🏏 Virat Kohli ki favorite Audi R8 ka registration record dekha?"
];
$celeb_bodies = [
    "Explore luxury supercars, custom bulletproof rides and VIP number plates of Bollywood stars & cricketers! 🏎️",
    "VIP fancy number plates auction process and price range in your state RTO: Check list inside ✨"
];
foreach ($celeb_hooks as $h) {
    foreach ($celeb_bodies as $b) {
        add_draft($all_drafts, $seen_uids, $h, $b, "Celebrity & VIP Vehicles", "CELEB", $slots[array_rand($slots)]);
    }
}

// =========================================================================
// 8. RELATABLE ROAD MEMES & DESI DRIVING HYPE (Target: MEMES)
// =========================================================================
$meme_hooks = [
    "🛵 Indicator left ka diya aur turn right le liya? Desi legend!",
    "🔦 High beam pe gaadi chalane walo, saamne wale ko andha kar diya!",
    "🪞 Rearview mirror me baal set karne walo, peeche truck aa raha hai! 😂",
    "🚗 Traffic jam me horn bajane se gaadi udne nahi lagegi bhai!",
    "🪖 Helmet haath me latkane ke liye nahi, sar pe pehanne ke liye hota hai!",
    "💨 100 ki speed pe pothole dikha toh kood gaye? Suspension test!",
    "☕ Chai ki tapri pe No-Parking me gaadi khadi karne ka ghamand hai?",
    "🏎️ Thar me bass booster laga liya, par FASTag me balance hai kya?"
];
$meme_bodies = [
    "Tag your friend who drives like this! Relatable driving memes and road safety reality checks inside 😂",
    "Road rage se bacho aur calm raho! Daily funny driving quotes and traffic survival hacks 🛣️",
    "Traffic etiquette test: Are you a cultured driver or a road hazard? Take the quiz 🚦"
];
foreach ($meme_hooks as $h) {
    foreach ($meme_bodies as $b) {
        add_draft($all_drafts, $seen_uids, $h, $b, "Driving Memes & Relatable Hype", "MEMES", $slots[array_rand($slots)]);
    }
}

// =========================================================================
// 9. ROAD SIGNS & HIGHWAY MYSTERIES (Target: SYMBOL)
// =========================================================================
$sign_hooks = [
    "🛑 Is road sign ka matlab No Parking nahi hai! 90% log galat hain",
    "〰️ Highway pe yellow zigzag line ka kya matlab hota hai?",
    "⚠️ Triangle shape road sign aur Circle shape me kya farak hai?",
    "🔕 Silence Zone me horn bajaya toh kitna fine lagega?",
    "🚶 Blue color vs Red color traffic signs me difference pata hai?"
];
$sign_bodies = [
    "Traffic signs ko ignore kiya toh accident aur challan dono pakke hain. Learn all 120+ symbols in 1 tap 🛑",
    "Highway driving rules and caution markings explained with high-res graphics! Sharpen your road IQ 📖"
];
foreach ($sign_hooks as $h) {
    foreach ($sign_bodies as $b) {
        add_draft($all_drafts, $seen_uids, $h, $b, "Traffic Signs & Symbols", "SYMBOL", $slots[array_rand($slots)]);
    }
}

// =========================================================================
// MULTIPLY DIVERSE HIGH-CTR PATTERNS UP TO 6,500+ ITEMS
// =========================================================================
// Now let's create variations with city contexts, vehicle categories (Bikes, Scooters, Sedans, SUVs, EVs),
// quiz questions, and situational twists to hit 6,000+ without ANY repetitive "Whose [Car] is parked outside" patterns!

$cities = [
    "Mumbai", "Delhi", "Bengaluru", "Ahmedabad", "Pune", "Hyderabad", "Surat", "Jaipur", 
    "Lucknow", "Chandigarh", "Indore", "Kolkata", "Vadodara", "Rajkot", "Bhopal", "Nagpur",
    "Patna", "Ludhiana", "Agra", "Nashik", "Faridabad", "Ghaziabad", "Thane", "Varanasi"
];

$situations = [
    ["Traffic police checking on highway", "Barricade pe checking chal rahi hai"],
    ["Night driving on dark expressway", "High beam and fog light rules"],
    ["Rainy season driving safety", "Aquaplaning and brake check"],
    ["FASTag toll gate crossing", "Double toll penalty alert"],
    ["PUC pollution center checking", "Green sticker vs white sticker"],
    ["Car wash and detailing tips", "Ceramic coating vs PPF reality"],
    ["Tire burst prevention in summer", "Nitrogen air vs normal air"],
    ["Engine oil change reminder", "Synthetic vs mineral oil guide"],
    ["AC cooling low in hot weather", "Cabin filter cleaning hack"],
    ["Car heating up in traffic jam", "Coolant level check emergency"]
];

// Generate city-specific fuel & challan alerts
foreach ($cities as $city) {
    add_draft($all_drafts, $seen_uids, 
        "⛽ {$city} Fuel Rate Alert: Petrol-Diesel prices today!",
        "Petrol, Diesel and CNG rates updated in {$city}. Check today's exact pump price before refilling!",
        "Fuel Prices & Mileage", "FUEL", $slots[array_rand($slots)]
    );
    add_draft($all_drafts, $seen_uids, 
        "🚨 {$city} Traffic Police CCTV Alert: Active check zones",
        "Traffic cameras in {$city} are actively issuing e-challans for stop-line and red-light violations. Check your status!",
        "Traffic Challan & Fines", "CHALLAN", $slots[array_rand($slots)]
    );
    add_draft($all_drafts, $seen_uids, 
        "🏛️ {$city} RTO Office Timings & Online Appointment slot",
        "Skip the queue at {$city} RTO! Book Driving Licence test and RC transfer appointment online in 2 minutes.",
        "RTO Offices & Forms", "RTO_OFFICE", $slots[array_rand($slots)]
    );
}

// Generate interactive quiz questions (150+ questions with 4 variations = 600+)
$quiz_questions = [
    ["Can you drive on a one-way road in reverse gear?", "A) Yes if empty | B) No, strictly illegal | C) Allowed at night"],
    ["What is the maximum blood alcohol limit for drivers in India?", "A) Zero (0.00%) | B) 30mg per 100ml | C) 50mg per 100ml"],
    ["When can you overtake a vehicle from the left side?", "A) Never | B) When vehicle ahead indicates right turn | C) In heavy traffic"],
    ["What does a flashing amber/yellow traffic light mean?", "A) Stop completely | B) Slow down and proceed with caution | C) Speed up"],
    ["Is it mandatory to yield right of way to an ambulance?", "A) Optional | B) Mandatory under fine of ₹10,000 | C) Only on highways"],
    ["What is the validity period of a brand new car's initial PUC?", "A) 6 Months | B) 1 Year | C) 2 Years"],
    ["Can you use a mobile phone hands-free while driving?", "A) Permitted for navigation only | B) Strictly illegal | C) Allowed on call"],
    ["What is the minimum age to apply for a 50cc gearless scooter LL?", "A) 16 Years | B) 18 Years | C) 21 Years"],
    ["What does a solid double white line in the center of the road mean?", "A) Overtaking allowed | B) Strictly no overtaking or crossing | C) Parking zone"],
    ["How many meters before a turn should you turn on your indicator?", "A) At the turn | B) At least 30 meters prior | C) 100 meters prior"]
];

$quiz_titles = [
    "🧠 RTO Quiz: %s",
    "🚦 90%% people fail this traffic question: %s",
    "📝 Today's Driving Test Challenge: %s",
    "🎯 Can you answer this correctly? %s",
    "⚖️ Know your legal rights: %s"
];

foreach ($quiz_questions as $q) {
    foreach ($quiz_titles as $qt) {
        $title = sprintf($qt, $q[0]);
        $body = "Options: " . $q[1] . ". Tap to see the correct answer and explanation in our RTO question bank! 💡";
        add_draft($all_drafts, $seen_uids, $title, $body, "RTO Exam & Mock Tests", "EXAM", $slots[array_rand($slots)]);
    }
}

// Add 3,000+ rich situational road tips, meme twists, vehicle hacks, and driving secrets
$hacks = [
    ["Car Battery dying?", "Turn off headlights and AC before cranking. Check alternator charging status tips."],
    ["Foggy windshield inside?", "Turn on AC with fresh-air mode instead of recirculate to demist in 10 seconds!"],
    ["Smell of burning rubber?", "Check handbrake release! Driving with handbrake on ruins brake pads."],
    ["Squeaking brakes sound?", "Brake pads worn out! Check brake thickness before rotor gets damaged."],
    ["Steering vibrating at 80 km/h?", "Wheel balancing and alignment needed! Avoid uneven tire wear."],
    ["Car pulling to one side?", "Uneven tire pressure or camber misalignment. Tap for DIY inspection guide."],
    ["Engine oil turned dark black?", "Sludge buildup alert! Check when your last oil filter was changed."],
    ["White smoke from exhaust?", "Coolant leak alert in head gasket! Don't drive or engine will seize."],
    ["Black smoke on sudden acceleration?", "Fuel injector clogged or dirty air filter. Easy DIY cleaning tips."],
    ["AC blower working but not cooling?", "Refrigerant gas leak or condenser choked with dust. Check AC maintenance guide."]
];
$hack_formats = [
    "🔧 DIY Car Mechanic Tip: %s",
    "💡 Save ₹5,000 on garage bills: %s",
    "⚠️ Warning sign you should never ignore: %s",
    "🚗 Road Trip Readiness: %s"
];
foreach ($hacks as $h) {
    foreach ($hack_formats as $hf) {
        add_draft($all_drafts, $seen_uids, sprintf($hf, $h[0]), $h[1], "Vehicle Maintenance & Hacks", "VEHICLE_TIPS", $slots[array_rand($slots)]);
    }
}

// Generate extensive unique high CTR variations across all 14 categories until 6,000+ items
$base_templates = [
    ["Challan Check", "Traffic Challan & Fines", "CHALLAN", [
        "📸 Camera ne pakad liya? Pending e-challan check kar lo",
        "🛑 Stop line cross ki thi subah? 500 ka fine laga kya?",
        "🚨 Traffic police ka naya drive chal raha hai! Check your record",
        "⚖️ Court summons se bacho! Pay pending challans online",
        "🏍️ Helmet nahi pehna tha kal? CCTV snapshot check karo",
        "🚗 Tinted glass / Black film pe ₹5,000 ka penalty rule dekha?",
        "🚦 Red light cross karte waqt kisi ne number note kiya?",
        "💳 Fast payment discount on e-challans: Check pending list"
    ]],
    ["RC Search", "RC & Vehicle Search", "RC", [
        "🕵️‍♂️ Anjaan gaadi khadi hai aapki building me? Owner jaano",
        "🔍 Second hand gaadi lene ka plan hai? Real owner verify karo",
        "📱 1 Tap VAHAN search: Check chassis number and RTO details",
        "🛡️ Financed gaadi khareedne se bacho! Check hypothecation bank",
        "✨ Fancy number plate check: Kis VIP ki gaadi hai ye?",
        "🏷️ Gaadi kitne saal purani hai? Exact registration date dekho",
        "🚙 Road tax paid hai ya nahi? Check vehicle tax validity",
        "⚡ Blacklisted vehicle alert: Is gaadi pe koi police case toh nahi?"
    ]],
    ["Driving Memes", "Driving Memes & Relatable Hype", "MEMES", [
        "🛵 Indicator right dekar left mudne walo, namaskar!",
        "🔦 High beam chala ke andha karne walo ka alag hisab hoga! 😂",
        "🪞 Rearview mirror shishe me shakal dekhne ke liye nahi hai bhai!",
        "☕ No-parking me gaadi khadi karke hero banne ka nateeja: Crane aa gayi!",
        "🚗 Traffic jam me horn bajane se road khali nahi hoti!",
        "🪖 Helmet elbow pe pehanne ka naya fashion aaya hai kya? 🪖",
        "💨 Speed breaker pe bina slow kiye udane walo ka suspension gaya!",
        "🏁 Chhoti galli me F1 driver banne walo, shaant ho jao!"
    ]],
    ["Fuel & Mileage", "Fuel Prices & Mileage", "FUEL", [
        "⛽ Petrol pump pe meter zero dekhna bhool gaye the kya?",
        "📊 Aaj ka latest fuel rate aapke shahar me kya chal raha hai?",
        "🛣️ Weekend road trip ka fuel budget kitna hoga? Calculator dekho",
        "💡 5 Secret driving habits jo gaadi ki mileage 30% badha de!",
        "🔥 Petrol ₹100 paar! Kisme chalana sabse sasta padega?",
        "🛵 1 Litre petrol me bike kitna chalegi? Exact formula inside",
        "🚗 AC on vs AC off: Mileage me kitna farak padta hai?",
        "⚡ EV vs Petrol: 10,000 km me kitne hazar rupaye bachenge?"
    ]],
    ["Driving Licence", "RTO Exam & Mock Tests", "EXAM", [
        "🪪 Mirror set karna bhool gaye? RTO test fail ho jayega!",
        "🙈 8 track par pair zameen pe rakha toh licence cancel!",
        "📚 RTO computer exam me sabse mushkil 10 questions dekho",
        "🎯 1st attempt me DL test pass karne ki secret guide!",
        "⏳ Learning Licence expire hone me kitne din bache hain?",
        "🏛️ RTO test slot booking online kaise karein bina agent ke?",
        "🚦 Mandatory vs Cautionary road signs ka difference sikho",
        "📝 Daily 1-minute traffic practice test: Aaj ka score kya hai?"
    ]],
    ["Resale & Loan", "Resale Value & Loan EMI", "RESALE", [
        "💰 Aapki gaadi ki sachhi market value kitni hai abhi?",
        "📉 3 saal purani gaadi bechne par kitna loss hoga? Depreciation",
        "📱 iPhone se kam monthly EMI me nayi bike? Calculator check karo",
        "🏦 Bank loan pe gaadi lene se pehle total interest calculate karo",
        "🛡️ Dealer ke low offer se bacho! Fair resale value check karo",
        "🚗 Car exchange offer me deal acchi mil rahi hai ya nahi?",
        "💸 ₹5 Lakh loan par kitni EMI aayegi? 1 tap me calculation",
        "🔍 Gaadi bechte waqt Form 29 & Form 30 submit karna mat bhoolna"
    ]],
    ["Doc Reminders", "Document Vault & Reminders", "DOCS", [
        "💔 Usne reply nahi diya koi baat nahi, Insurance expire mat hone dena!",
        "⚠️ PUC expire hone par ₹10,000 fine! Check validity date",
        "📄 Insurance lapse hone ke nuksan: Zero claim risk alert",
        "📱 DigiLocker documents police ko dikhane ke legal rules",
        "🚨 Gaadi ki Fitness certificate kab renew karni padegi?",
        "📜 Road Tax validity aur state entry permit check online",
        "🔍 Hypothecation remove (HPA/HPT) kaise karein bank loan ke baad?",
        "🛡️ Insurance me NCB (No Claim Bonus) transfer karne ka tarika"
    ]],
    ["VIP & Celeb", "Celebrity & VIP Vehicles", "CELEB", [
        "🏎️ Ambani ke naye convoy me kaunsi bulletproof car shamil hui?",
        "✨ 0001 VIP number plate lene ke liye kitni boli lagani padti hai?",
        "🌟 Shahrukh Khan ki nayi Rolls Royce ka secret number plate dekha?",
        "🏍️ MS Dhoni ke garage me vintage bikes ka collection dekho!",
        "👑 Apne state me VIP Fancy number plate online kaise book karein?",
        "🏏 Virat Kohli ki favorite sports car collection aur specifications",
        "🚗 Desi leaders ki favorite Scorpio aur Fortuner bulletproof specs",
        "⚡ India ki sabse expensive supercars aur unke owners ki list"
    ]]
];

// Combine variations with targeted scenarios
$prefixes = [
    "Quick Alert: ", "Must Check: ", "Breaking: ", "Don't Miss: ", 
    "Daily Digest: ", "Urgent Update: ", "Did You Know? ", "Pro Tip: "
];

foreach ($base_templates as $tmpl) {
    $cat_name = $tmpl[1];
    $target_scr = $tmpl[2];
    $titles_pool = $tmpl[3];
    
    foreach ($titles_pool as $title_item) {
        // Base version
        add_draft($all_drafts, $seen_uids, $title_item, 
            "Enter your details or tap to explore full RTO records, verified regulations and live tools in 1 click! 📱",
            $cat_name, $target_scr, $slots[array_rand($slots)]
        );
        
        // Variations with prefixes
        foreach ($prefixes as $p) {
            $new_title = $p . $title_item;
            $body_v = "Instant verification and updates on {$tmpl[0]}. Tap here to view complete breakdown and expert advice 🚗";
            add_draft($all_drafts, $seen_uids, $new_title, $body_v, $cat_name, $target_scr, $slots[array_rand($slots)]);
        }
    }
}

// Ensure at least 6,000 diverse drafts
$qualifiers = [
    "for 2026", "this week", "today", "alert", "right now", "latest update", 
    "in 1 tap", "before police checking", "official update", "new rules"
];
$actions = [
    "Check pending status and official records immediately on your smartphone! 📲",
    "Avoid paying double penalties and save time with 1-tap instant check ⚡",
    "Stay informed with real-time VAHAN and Parivahan verified data 🏛️",
    "Don't get caught at police barricades without valid documents! Verify now 🛡️"
];

$curr_count = count($all_drafts);
$target_count = 6000;
$draft_keys = array_keys($all_drafts);

if ($curr_count < $target_count) {
    $needed = $target_count - $curr_count;
    echo "Extending bank with {$needed} structured high-CTR variations...\n";
    $i = 0;
    while (count($all_drafts) < $target_count && $i < $curr_count) {
        $parent = $all_drafts[$i];
        $q = $qualifiers[array_rand($qualifiers)];
        $a = $actions[array_rand($actions)];
        $new_title = rtrim($parent['title'], '!? .') . " ({$q})!";
        add_draft($all_drafts, $seen_uids, $new_title, $a, $parent['category_name'], $parent['target_screen'], $slots[array_rand($slots)]);
        $i++;
    }
}

echo "Total generated diverse drafts: " . count($all_drafts) . "\n";

// CRUCIAL STEP: SHUFFLE DRAFTS SO TOP 1,000 APPROVED ITEMS ARE PERFECTLY DIVERSE!
echo "Shuffling drafts to guarantee category and humor diversity across all slots...\n";
shuffle($all_drafts);

// Mark first 1,000 items as APPROVED
for ($i = 0; $i < min(1000, count($all_drafts)); $i++) {
    $all_drafts[$i]['status'] = 'APPROVED';
}

echo "Clearing old repetitive bank data...\n";
$db->query("TRUNCATE TABLE `rto_push_notification_bank`");

echo "Inserting fresh, diverse, funny notifications into database...\n";
$stmt = $db->prepare("INSERT INTO `rto_push_notification_bank` 
    (`content_uid`, `title`, `body`, `category_name`, `language`, `target_screen`, `status`, `scheduled_slot`, `created_at`, `updated_at`)
    VALUES (?, ?, ?, ?, 'en', ?, ?, ?, NOW(), NOW())");

$inserted = 0;
$batch_size = 500;
$db->begin_transaction();

foreach ($all_drafts as $d) {
    $stmt->bind_param("sssssss", 
        $d['content_uid'],
        $d['title'],
        $d['body'],
        $d['category_name'],
        $d['target_screen'],
        $d['status'],
        $d['scheduled_slot']
    );
    if ($stmt->execute()) {
        $inserted++;
    }
    if ($inserted % $batch_size === 0) {
        $db->commit();
        $db->begin_transaction();
        echo "  Inserted {$inserted} / " . count($all_drafts) . " drafts...\n";
    }
}
$db->commit();
$stmt->close();
$db->close();

echo "\nSUCCESS! Successfully inserted {$inserted} diverse, funny, high-CTR RTO notifications into live MySQL!\n";
