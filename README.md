# 🏠 RoomConnect | Data-Driven Student Roommate Matcher

A digital business model and web platform designed for verified students at **KU Eichstätt-Ingolstadt** and **THI (Technical University of Ingolstadt)**. RoomConnect replaces random room listings with a quantitative lifestyle compatibility engine, streamlining the housing journey for students and landlords alike. Built as a collaborative project by a **team of 5** for the *Digital Business Model & Technologies* course.

---

## 📦 Technologies

- `JavaScript (ES6+)`
- `Data Analytics & Weighted Scoring Algorithm`
- `Luhn Algorithm (Checksum Validation)`
- `HTML5 / Semantic Web`
- `CSS3 / Bootstrap 5.3`
- `PHP / Session Management`

---

## 🦄 Features

Here is what RoomConnect delivers through its data-driven platform:

- **Verified Student Access Gate:** Filters users via institutional emails (`@stud.ku.de`, `@thi.de`) to guarantee a 100% verified student network before quiz entry.
- **Quantitative Lifestyle Survey:** Collects structured data across 7 lifestyle dimensions (cleanliness, communication, sleep schedule, noise, social habits, smoking, study style).
- **Compatibility Scoring Engine:** Calculates weighted similarity metrics between profiles to rank roommate matches dynamically with percentage scores (e.g., 99% match).
- **Mutual Match Acceptance Handshake:** Requires two-way student agreement before unlocking direct communication channels and shared room selection.
- **Shared Student Dashboard:** Multi-user interface (`lessons.php`) providing state tracking, matched-partner chat simulation, and joint decision management.
- **Tiered Pricing & Luhn Card Validation:** Integrates client-side Luhn algorithm checksum testing to validate payment card inputs for Premium plan upgrades without backend dependency.

---

## 👥 Teamwork & Role Breakdown (Team of 5)

RoomConnect was developed by a cross-functional team of 5 students, dividing responsibilities across business logic, web architecture, and data engineering:

| Member Name | Business & Website Design Responsibilities |
| :--- | :--- |
| **Maria Kulikova** | About page, mobile responsiveness adaptation, platform logic, and brand style development. |
| **Supreeya Boriphonmongkol** | Site framework architecture, verification gate, Index page, and Shared Dashboard page (`lessons.php`). |
| **Godfred Boateng** | Trust & Safety page, platform safety protocols, and revenue model strategy. |
| **Juliet Alufuo** *(Data Analyst Lead)* | Quiz page design, data-driven matching algorithm, and lifestyle compatibility scoring logic. |
| **Kausar Akther** | Pricing page design, plan tier structure, and client-side Luhn payment validation logic. |
| **All Team Members** | Core digital business model logic, value proposition canvas, and student-landlord market alignment. |

---

## 👨‍🍳 The Process

- **Problem & Data Structuring:** Converted qualitative student living habits into 7 quantitative 1–5 Likert-scale metrics.
- **Algorithm & Scoring Engine:** Implemented weighted similarity scoring to calculate compatibility percentages and rank roommate matches dynamically.
- **Trust & Payment Architecture:** Built institutional email verification (`@stud.ku.de`, `@thi.de`) and client-side Luhn algorithm card validation for mock payment processing.
- **Shared Dashboard & Funnel Integration:** Developed multi-user session state tracking and evaluated user conversion across the 5-stage housing journey.

---

## 📚 What I Learned

During this project, I gained practical experience bridging business strategy with data logic and technical execution:

### 🧠 Data-Driven Matching Algorithms
- **Algorithmic Distance Scoring:** Normalized multi-variable survey responses and applied weighted distance formulas to rank compatible profiles accurately.
- **Conflict Penalty Weighting:** Implemented variance penalties for high-impact factors (e.g., smoking intolerance) to increase real-world match stability.

### 📐 Data Integrity & Validation Math
- **Luhn Algorithm Checksum:** Implemented numeric checksum validation for credit card numbers to verify input integrity on client-side form submissions.
- **Survey Metric Standardization:** Designed 5-point Likert scale options to eliminate response ambiguity and capture reliable quantitative data.

### 🤝 Cross-Functional Team Collaboration
- **Cross-Role Communication:** Collaborated across all 5 team members to map business logic directly to page UI elements and user flows.
- **Structured Deliverables:** Produced pitch slides, architectural decision records (ADRs), and visual design guidebooks under strict course timelines.

### 📊 Business Intelligence & Metric Modeling
- **Funnel Analytics:** Mapped conversion milestones across onboarding stages (*Verification ➔ Quiz ➔ Match ➔ Shared Selection ➔ Approval*) to minimize user drop-off.

---

## 📈 Overall Growth

This project expanded my capability as a Data Analyst to think holistically about digital product architecture. Beyond statistical analysis, I learned how data validation, algorithmic scoring, and trust mechanisms directly drive digital business model viability and deliver tangible value for end-users.

---

## 💭 How can it be improved?

- **Machine Learning Recommendations:** Incorporate collaborative filtering models trained on post-move-in feedback to refine attribute weighting dynamically.
- **Backend API & Real-time Sockets:** Transition from client-side simulation to Python FastAPI / Node.js with WebSockets for live chat and instant match notifications.
- **Landlord Analytics Portal:** Build a dedicated data dashboard for landlords showing tenancy stability metrics and verified group profiles.
- **Geographic Distance Integration:** Add geospatial mapping (GIS) to calculate commute times from matched rooms to KU/THI campuses.

---

## 🚦 Running the Project

To run RoomConnect locally on your machine:

1. **Clone or Download the Repository:**
   ```bash
   git clone https://github.com/your-username/roomconnect.git
   cd roomconnect
   ```

2. **Open in Browser:**
   - Double-click `index.html` to launch directly in any modern browser (Chrome, Edge, Firefox).
   - *Optional:* Serve via a local web server (e.g. Live Server extension in VS Code, or PHP local server `php -S localhost:8000`).

3. **Explore the Journey:**
   - Enter a mock email ending in `@stud.ku.de` or `@thi.de` on the home page.
   - Complete the **Quiz** (`quiz.html`) to trigger the compatibility matching engine.
   - Test match acceptance, shared dashboard (`lessons.php`), and pricing validation (`pricing.html`).

---

## 📄 **Pitch Deck:** `RoomConnect_Final_BusinessModel.pptx`

