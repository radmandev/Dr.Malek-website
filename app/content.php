<?php
// Default site content. Every top-level key is one editable section in /admin.
// When the database is configured, rows in `site_content` (section -> JSON) override these.
// Empty strings ('') are rendered as clearly labelled placeholders, never invented data.
//
// Notes on specific sections:
//   stats                  Only verifiable figures. `value` is animated; `suffix` follows it.
//   achievements.reflection Dr. Malek's own words, from his published 50th-case milestone post.
//   academicContributions  Free-text entries, e.g. "2027 — Co-author, ...". Add via admin.

defined('APP_ROOT') || exit;

return 
[
    'profile' => [
        'name' => 'Dr. Malek Gaashan',
        'postnominals' => 'MD | MRCS | FRCS (Urol)',
        'position' => 'Consultant Urological Surgeon',
        'focus' => 'Robotic Surgery | Uro-Oncology | Intracorporeal Reconstruction',
        'heroHeadline' => 'Advancing the Future of Robotic Urological Surgery',
        'heroText' => 'Specialist expertise in robotic bladder cancer surgery, complex pelvic reconstruction, and intracorporeal urinary diversion.',
        'portrait' => '/images/dr-malek-gaashan-portrait.jpg',
        'avatar' => '/images/dr-malek-gaashan-avatar.jpg',
        'introTitle' => 'Precision in Surgery. Excellence in Care.',
        'introBody' => [
            'Dr. Malek Gaashan is a Consultant Urological Surgeon at University Hospitals of Leicester NHS Trust, with a practice centred on uro-oncology and advanced pelvic robotic surgery.',
            'His training and career span three health systems — Jordan, Saudi Arabia and the United Kingdom — including fellowship-level onco-urology training at King Hussein Cancer Center. That breadth informs a practice built on careful patient selection, meticulous technique and close multidisciplinary collaboration.',
            'He has a particular commitment to robotic radical cystectomy with intracorporeal urinary diversion, and to the mentorship and structured learning that allow complex minimally invasive surgery to be delivered safely and consistently.',
        ],
        'philosophy' => 'Every operation begins long before the first incision — with a clear conversation, an honest assessment of the options, and a plan built around the individual patient.',
        'bioBody' => [
            'Dr. Malek Gaashan is a Consultant Urological Surgeon with extensive experience in urology, uro-oncology, and advanced robotic surgery.',
            'Since August 2023 he has served as Consultant Urology, Oncology and Pelvic Robotic Surgeon at University Hospitals of Leicester NHS Trust, where his work focuses on robotic radical cystectomy and intracorporeal reconstruction of the urinary tract.',
            'His surgical foundation was laid during five years of urology residency in Jordan, followed by onco-urology fellowship training at King Hussein Cancer Center — one of the region\'s leading comprehensive cancer centres — and senior registrar practice with the Ministry of Health in Saudi Arabia.',
        ],
        'interests' => [
            'Robotic radical cystectomy',
            'Bladder cancer treatment',
            'Intracorporeal urinary diversion',
            'Complex urinary reconstruction',
            'Minimally invasive urological surgery',
        ],
    ],
    'credentials' => [
        'Consultant Urological Surgeon',
        'FRCS (Urology)',
        'Robotic Cystectomy',
        'Bladder Cancer Surgery',
        'Intracorporeal Reconstruction',
    ],
    'stats' => [
        [
            'value' => 50,
            'suffix' => '+',
            'label' => 'Robotic cystectomies',
            'note' => 'Published career milestone',
        ],
        [
            'value' => 3,
            'suffix' => '',
            'label' => 'Health systems',
            'note' => 'United Kingdom · Saudi Arabia · Jordan',
        ],
        [
            'value' => 2012,
            'suffix' => '',
            'label' => 'Urology training began',
            'note' => 'Specialty Hospital, Jordan',
        ],
    ],
    'expertise' => [
        [
            'slug' => 'robotic-radical-cystectomy',
            'title' => 'Robotic Radical Cystectomy',
            'summary' => 'Removal of the bladder for bladder cancer using robotic-assisted, minimally invasive surgery.',
            'image' => '/images/robotic-operating-theatre.jpg',
            'imageLabel' => 'Robotic operating theatre',
            'imagePosition' => 'center',
            'imageZoom' => 1,
            'body' => [
                'Radical cystectomy — removal of the bladder together with the surrounding lymph nodes — is a principal treatment for muscle-invasive bladder cancer and for selected high-risk non-muscle-invasive disease.',
                'Robotic assistance gives the surgeon a magnified, three-dimensional view of the pelvis and wristed instruments that move with fine control through small incisions. The surgeon remains in full control of every movement throughout the operation.',
                'Each operation is planned around the individual: the stage and location of the cancer, previous treatment such as chemotherapy, general health, and the patient\'s own priorities for life after surgery. Recommendations are made within a multidisciplinary team.',
            ],
            'points' => [
                'Male and female cystectomy approaches',
                'Pelvic lymph node dissection',
                'Planning within a multidisciplinary cancer team',
                'Enhanced recovery pathways',
            ],
        ],
        [
            'slug' => 'intracorporeal-urinary-diversion',
            'title' => 'Intracorporeal Urinary Diversion',
            'summary' => 'Reconstruction of the urinary tract performed entirely inside the body with robotic assistance.',
            'image' => '/images/robotic-operating-theatre.jpg',
            'imageLabel' => 'Da Vinci robotic surgical system',
            'imagePosition' => '12% 45%',
            'imageZoom' => 1.9,
            'body' => [
                'After the bladder is removed, a new way for urine to leave the body must be created. In intracorporeal diversion, this reconstruction is completed entirely inside the abdomen using the robotic system, rather than through a larger open incision.',
                'This approach requires advanced reconstructive skill and is one of the more technically demanding areas of robotic urology. The choice of diversion is made together with the patient, balancing medical suitability with lifestyle and personal preference.',
            ],
            'points' => [
                'Intracorporeal ileal conduit',
                'Intracorporeal orthotopic neobladder reconstruction',
                'Shared decision-making on diversion type',
                'Stoma and continence nurse specialist support',
            ],
        ],
        [
            'slug' => 'complex-bladder-reconstruction',
            'title' => 'Complex Bladder Reconstruction',
            'summary' => 'Individualised reconstructive approaches for complex pelvic and urinary tract surgery.',
            'image' => '/images/robotic-operating-theatre.jpg',
            'imageLabel' => 'Robotic surgical system',
            'imagePosition' => '62% 30%',
            'imageZoom' => 1.7,
            'body' => [
                'Some patients present with anatomy or medical histories that make reconstruction more complex — previous pelvic surgery or radiotherapy, prior diversions, or coexisting conditions.',
                'In these situations there is rarely a single standard answer. Surgical planning is individualised, informed by detailed imaging and multidisciplinary discussion, with the aim of restoring function while keeping treatment as safe as possible.',
            ],
            'points' => [
                'Detailed pre-operative assessment and imaging review',
                'Individualised reconstructive planning',
                'Collaboration with colorectal, oncology and nursing teams',
            ],
        ],
        [
            'slug' => 'uro-oncology',
            'title' => 'Uro-Oncology',
            'summary' => 'Specialist experience in bladder cancer and complex urological oncology.',
            'image' => '/images/urology.jpg',
            'imageLabel' => 'Uro-oncology',
            'imagePosition' => '100% 55%',
            'imageZoom' => 1.6,
            'body' => [
                'Dr. Malek\'s training includes onco-urology fellowship work at King Hussein Cancer Center in Jordan and consultant practice in uro-oncology at University Hospitals of Leicester.',
                'Modern cancer care is delivered by teams. Surgery is one part of a pathway that may also include chemotherapy, radiotherapy, immunotherapy or surveillance, and decisions are made within a multidisciplinary meeting in line with current evidence-based guidelines.',
            ],
            'points' => [
                'Bladder cancer across the disease spectrum',
                'Upper urinary tract surgery, including laparoscopic nephroureterectomy',
                'Multidisciplinary, guideline-based decision-making',
            ],
        ],
        [
            'slug' => 'organ-sparing-approaches',
            'title' => 'Organ-Sparing Surgical Approaches',
            'summary' => 'Carefully selected organ-preserving techniques, where clinically appropriate.',
            'image' => '',
            'imageLabel' => 'Clinical consultation environment',
            'imagePosition' => 'center',
            'imageZoom' => 1,
            'body' => [
                'For selected patients, it may be possible to preserve structures that would otherwise be removed — for example, nerve-sparing or pelvic organ-sparing techniques during cystectomy — with the aim of supporting sexual function, continence or quality of life.',
                'These approaches are only appropriate when they do not compromise cancer control. Suitability depends on the stage and position of the tumour and on individual anatomy, and is discussed openly with each patient. Not every patient will be a candidate.',
            ],
            'points' => [
                'Prostate capsule-sparing cystectomy in selected men',
                'Sexual organ-sparing cystectomy in selected women',
                'Nerve-sparing techniques where oncologically safe',
                'Cancer control always takes priority',
            ],
        ],
    ],
    'timeline' => [
        [
            'organisation' => 'University Hospitals of Leicester NHS Trust',
            'location' => 'United Kingdom',
            'roles' => [
                [
                    'title' => 'Consultant Urology / Oncology / Pelvic Robotic Surgeon',
                    'period' => 'August 2023 – Present',
                ],
                [
                    'title' => 'Associate Specialist in Urology',
                    'period' => 'January 2022 – July 2023',
                ],
            ],
        ],
        [
            'organisation' => 'Ministry of Health',
            'location' => 'Saudi Arabia',
            'roles' => [
                [
                    'title' => 'Urologist / Senior Registrar',
                    'period' => 'January 2021 – February 2022',
                ],
            ],
        ],
        [
            'organisation' => 'King Hussein Cancer Center',
            'location' => 'Amman, Jordan',
            'roles' => [
                [
                    'title' => 'Urology Specialist / Onco-Urology Fellow',
                    'period' => 'April 2018 – January 2021',
                ],
            ],
        ],
        [
            'organisation' => 'Private Practice',
            'location' => 'Amman, Jordan',
            'roles' => [
                [
                    'title' => 'Urologist',
                    'period' => 'July 2017 – March 2018',
                ],
            ],
        ],
        [
            'organisation' => 'Specialty Hospital',
            'location' => 'Jordan',
            'roles' => [
                [
                    'title' => 'Urology Resident',
                    'period' => 'July 2012 – June 2017',
                ],
            ],
        ],
    ],
    'education' => [
        [
            'title' => 'FRCS (Urology)',
            'institution' => 'Royal College of Surgeons in Ireland',
            'year' => '2024 – Present',
        ],
        [
            'title' => 'MRCS',
            'institution' => 'Royal College of Surgeons in Ireland',
            'year' => '2020',
        ],
        [
            'title' => 'Diploma in Laparoscopy',
            'institution' => 'University of Strasbourg',
            'year' => '2016',
        ],
        [
            'title' => 'Arab Board of Urology',
            'institution' => 'Arab Board of Health Specializations',
            'year' => '',
        ],
        [
            'title' => 'Jordan Board of Urology',
            'institution' => 'Jordan Medical Council',
            'year' => '',
        ],
        [
            'title' => 'MBBS',
            'institution' => 'Mutah University, Jordan',
            'year' => '2005 – 2011',
        ],
    ],
    'registrations' => [
        [
            'body' => 'General Medical Council (GMC)',
            'detail' => '',
        ],
        [
            'body' => 'Additional professional registrations',
            'detail' => '',
        ],
    ],
    'awards' => [
        [
            'title' => 'First Rank — Jordan Urology Board Examination',
            'organisation' => 'Jordan Medical Council',
            'year' => '2018',
        ],
        [
            'title' => 'Best Resident Award',
            'organisation' => 'Specialty Hospital',
            'year' => '2016',
        ],
        [
            'title' => 'Dean\'s Honor List',
            'organisation' => 'Mutah University',
            'year' => '',
        ],
    ],
    'achievements' => [
        'headline' => '50+ Robotic Cystectomies',
        'statement' => 'A milestone built on precision, continuous learning, collaboration, and dedication to advancing robotic bladder cancer surgery.',
        'reflection' => 'Each case is a reminder of the privilege and responsibility we carry as surgeons to improve patient outcomes through precision, skill, and compassion.',
        'teamQuote' => 'Complex surgery is never a one-person achievement — it is the result of a great team working towards a common goal.',
        'approachesIntro' => 'Every case is different. In a single week, three complex robotic cystectomies required three different surgical approaches — each tailored to the individual patient while striving for excellent oncological outcomes and quality of life.',
        'approaches' => [
            [
                'title' => 'Prostate capsule-sparing male cystectomy',
                'detail' => 'Robotic male cystectomy with prostate capsule-sparing and intracorporeal neobladder reconstruction.',
            ],
            [
                'title' => 'Sexual organ-sparing female cystectomy',
                'detail' => 'Robotic female sexual organ-sparing cystectomy with intracorporeal ileal conduit formation.',
            ],
            [
                'title' => 'Urethro-cystoprostatectomy',
                'detail' => 'Robotic urethro-cystoprostatectomy with intracorporeal ileal conduit, including in older patients.',
            ],
        ],
        'milestones' => [
            [
                'title' => '50th robotic cystectomy with intracorporeal urinary diversion',
                'detail' => 'A career milestone in robotic bladder cancer surgery with fully intracorporeal reconstruction.',
            ],
            [
                'title' => 'Advanced robotic cystectomy practice',
                'detail' => 'Consultant-led robotic cystectomy practice at University Hospitals of Leicester NHS Trust.',
            ],
            [
                'title' => 'Male and female robotic cystectomy',
                'detail' => 'Experience across complex male and female robotic cystectomy approaches.',
            ],
            [
                'title' => 'Intracorporeal neobladder and ileal conduit',
                'detail' => 'Both principal forms of urinary diversion reconstructed entirely inside the body.',
            ],
        ],
        'cases' => [
            [
                'title' => 'The robotic theatre',
                'caption' => 'Robotic-assisted surgery in progress — no patient is identifiable.',
                'image' => '/images/robotic-operating-theatre.jpg',
            ],
            [
                'title' => 'Team & mentorship',
                'caption' => 'With the robotic theatre team.',
                'image' => '/images/robotic-theatre-team.jpg',
            ],
        ],
    ],
    'publications' => [
        [
            'title' => 'Atypical Presentation of Renal Medullary Carcinoma: A Case Report and Review of the Literature',
            'year' => '2018',
            'journal' => '',
            'doi' => '',
            'url' => '',
        ],
        [
            'title' => 'A Purely Laparoscopic Nephroureterectomy with Bladder Cuff Using Three Ports Including the Camera Port and Same Position: A New Technique',
            'year' => '2018',
            'journal' => '',
            'doi' => '',
            'url' => '',
        ],
    ],
    'academicContributions' => [],
    'conferences' => [
        [
            'featured' => true,
            'event' => 'AAU 2026',
            'location' => 'Jeddah, Saudi Arabia',
            'date' => '2026',
            'title' => 'Mastering Robotic Cystectomy and Intracorporeal Reconstruction: Learning Curve, Mentorship, and Practical Tips',
            'type' => 'Oral Presentation',
            'theme' => 'Laparoscopy, Robotics & Technical Advances in Urology',
            'summary' => 'Practical lessons from developing expertise in robotic radical cystectomy and intracorporeal urinary diversion — the importance of structured mentorship, overcoming the learning curve, and practical tips to help surgeons progress safely and effectively.',
            'image' => '',
        ],
    ],
    'patientArticles' => [
        [
            'slug' => 'understanding-bladder-cancer',
            'title' => 'Understanding Bladder Cancer',
            'image' => '',
            'imageAlt' => '',
            'body' => [
                'Bladder cancer begins in the cells lining the inside of the bladder. The most common first sign is blood in the urine (haematuria), which is often painless and may come and go. Blood in the urine has many possible causes, most of them not cancer, but it should always be checked by a doctor.',
                'Bladder cancers are broadly described as non-muscle-invasive, where the cancer is confined to the inner lining, or muscle-invasive, where it has grown into the muscle wall of the bladder. This distinction strongly influences which treatments are recommended.',
                'Diagnosis usually involves a camera examination of the bladder (cystoscopy), imaging scans, and removal of the tumour for analysis under the microscope. Treatment is then planned by a multidisciplinary team of specialists.',
            ],
        ],
        [
            'slug' => 'what-is-robotic-surgery',
            'title' => 'What Is Robotic Surgery?',
            'image' => '/images/robotic-surgery-diagram.jpg',
            'imageAlt' => 'Diagram of a robotic operating theatre: the surgeon at the computer console, the camera view, the high-definition 3D camera, the surgical cart with robotic instruments, the patient and a nurse.',
            'body' => [
                'Robotic surgery is a form of keyhole (minimally invasive) surgery. The surgeon sits at a console in the operating theatre and controls slim instruments and a high-definition 3D camera inserted through small incisions.',
                'The robot does not operate on its own — every movement is made by the surgeon. The system filters natural hand tremor and allows instruments to bend and rotate in ways that can help with precise work deep in the pelvis.',
                'Potential benefits can include smaller scars, reduced blood loss and, for some patients, a shorter hospital stay. However, robotic surgery is not suitable for everyone, and recovery and outcomes depend on many individual factors.',
            ],
        ],
        [
            'slug' => 'understanding-robotic-cystectomy',
            'title' => 'Understanding Robotic Cystectomy',
            'image' => '',
            'imageAlt' => '',
            'body' => [
                'A radical cystectomy removes the bladder and nearby lymph nodes. In men, the prostate and seminal vesicles are usually removed as well; in women, the operation may include removal of the uterus, ovaries and part of the vagina, although organ-sparing options may be considered in selected cases.',
                'Because the bladder is removed, a new route for urine — a urinary diversion — is created during the same operation. Some patients receive chemotherapy before surgery to improve the chance of cure.',
                'Most hospitals use an enhanced recovery programme to help patients get up, eat and drink sooner after surgery. Your team will explain what to expect in your own case.',
            ],
        ],
        [
            'slug' => 'what-is-an-ileal-conduit',
            'title' => 'What Is an Ileal Conduit?',
            'image' => '',
            'imageAlt' => '',
            'body' => [
                'An ileal conduit is the most common type of urinary diversion. A short piece of the small bowel (ileum) is separated and used as a channel. The ureters, which carry urine from the kidneys, are joined to one end, and the other end is brought out through the skin of the abdomen as a stoma.',
                'Urine drains continuously into a discreet bag worn under clothing. Specialist stoma nurses provide training and support before and after surgery, and most people return to their usual activities over time.',
            ],
        ],
        [
            'slug' => 'what-is-an-orthotopic-neobladder',
            'title' => 'What Is an Orthotopic Neobladder?',
            'image' => '',
            'imageAlt' => '',
            'body' => [
                'An orthotopic neobladder is a new bladder made from a longer segment of small bowel, shaped into a reservoir and connected to the urethra. This allows urine to be passed through the urethra rather than through a stoma.',
                'Emptying a neobladder involves relaxing the pelvic floor and gentle abdominal straining, which takes time to learn. Continence, particularly at night, often improves gradually, and some people need to use a catheter at times.',
                'A neobladder is not suitable for everyone. Kidney function, the position of the cancer, general health and personal preference all influence whether it is an appropriate option.',
            ],
        ],
        [
            'slug' => 'preparing-for-a-consultation',
            'title' => 'Preparing for a Urology Consultation',
            'image' => '',
            'imageAlt' => '',
            'body' => [
                'Bring any referral letters, recent scan or test results, and a list of your current medications, including anything bought over the counter.',
                'It can help to write your questions down beforehand and to bring a relative or friend to listen and take notes.',
                'Let the team know about previous operations, allergies, and anything that matters to you about your treatment and recovery — your priorities are an important part of planning.',
            ],
        ],
    ],
    'faqs' => [
        [
            'question' => 'Does the robot perform the operation?',
            'answer' => 'No. The surgeon controls every movement of the instruments from the console throughout the operation. The robotic system is a highly precise tool, not an autonomous device.',
        ],
        [
            'question' => 'Am I suitable for robotic surgery?',
            'answer' => 'Suitability depends on your diagnosis, general health and previous treatment. This can only be determined after an individual assessment by your clinical team.',
        ],
        [
            'question' => 'How long is recovery after a cystectomy?',
            'answer' => 'Recovery varies considerably between individuals. Your team will discuss what to expect, including hospital stay and return to normal activities, based on your own circumstances.',
        ],
        [
            'question' => 'Which type of urinary diversion is best?',
            'answer' => 'There is no single best option. The choice between an ileal conduit and a neobladder depends on medical factors and personal preference, and is made together with you.',
        ],
        [
            'question' => 'Can I get medical advice through this website?',
            'answer' => 'No. This website is for general information only. Please seek advice from your GP or treating team about your own health. In an emergency, call 999 in the UK or your local emergency number.',
        ],
    ],
    'contact' => [
        'institution' => 'University Hospitals of Leicester NHS Trust',
        'department' => 'Department of Urology',
        'address' => '',
        'email' => '',
        'secretaryPhone' => '',
        'linkedin' => 'https://www.linkedin.com/in/malek-gaashan-md-mrcs-frcs-urol-0061a9117',
        'gmcNumber' => '',
    ],
];
