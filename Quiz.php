<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .fact {
            display: none; /* Hide facts initially */
            margin-top: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <header>
        <a href="index.php"><img src="LogoHeader.png" alt="Logo" height=50 width=150></a>
        <h1>Quiz</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="Learn.php">Get the Facts</a></li>
            </ul>
        </nav>
    </header>

    <section>
        <h1>STD/STI Quiz</h1>

        <div class="quiz">
            <div class="question">Are Sexually transmitted diseases (STDs) and Sexually transmitted infections (STIs) the same thing?</div>
            <div class="options">
                <button onclick="showFact('fact1')">A True</button>
                <button onclick="showFact('fact1')">B False</button>
            </div>
            <div class="fact" id="fact1">Sexually transmitted diseases (STDs) are the same thing as what many medical professionals refer to as Sexually transmitted infections (STIs).</div>
        </div>

        <div class="quiz">
            <div class="question">Who can get an STD?</div>
            <div class="options">
                <button onclick="showFact('fact2')">A 15-25</button>
                <button onclick="showFact('fact2')">B 26-55</button>
                <button onclick="showFact('fact2')">C 55+</button>
                <button onclick="showFact('fact2')">D All of the above</button>
            </div>
            <div class="fact" id="fact2">While the CDC says that almost half of the reports come from 15-24 year olds, anyone can get them regardless of their race, age, gender, or sexual practice.</div>
        </div>

        <div class="quiz">
            <div class="question">All STIs have symptoms?</div>
            <div class="options">
                <button onclick="showFact('fact3')">A Yes</button>
                <button onclick="showFact('fact3')">B No</button>
            </div>
            <div class="fact" id="fact3">No, a lot of STIs are asymptomatic. This means that you could be contagious and pass the infection to others even if you have no symptoms.</div>
        </div>

        <div class="quiz">
            <div class="question">Can you get an STI from having oral sex?</div>
            <div class="options">
                <button onclick="showFact('fact4')">A Yes</button>
                <button onclick="showFact('fact4')">B No</button>
            </div>
            <div class="fact" id="fact4">STIs can be transmitted by vaginal, oral, anal sex, or even skin-to-skin contact. Gonorrhea, chlamydia, and genital herpes can be spread via oral sex.</div>
        </div>

        <div class="quiz">
            <div class="question">How many sexual partners does it take to get an STI?</div>
            <div class="options">
                <button onclick="showFact('fact5')">A 1-5</button>
                <button onclick="showFact('fact5')">B 5-10</button>
                <button onclick="showFact('fact5')">C 10+</button>
                <button onclick="showFact('fact5')">D All of the above</button>
            </div>
            <div class="fact" id="fact5">If protection is not being used, then the risk is higher, but it only takes one time to acquire an STI.</div>
        </div>

    </section>

        <footer>
            <p>&copy; 2025 STD Notification Service - Aware Link. All rights reserved.</p>
        </footer>

        <script>
            function showFact(factId) {
                // Hide all facts
                document.querySelectorAll('.fact').forEach(fact => {
                    fact.style.display = 'none';
                });
                
                // Show the selected fact
                document.getElementById(factId).style.display = 'block';
            }

                // Show the selected fact
                document.getElementById(factId).style.display = 'block';
            }
        </script>
    </body>
</html>
