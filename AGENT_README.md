# Standalone Project Webpage & Explainer Guide (`AGENT_README.md`)

> **Critical Notice for AI Assistants (Cursor, Antigravity, Claude Code, Copilot) & Collaborators:**
> 1. **Project Purpose:** This repository is strictly for hosting the **research publication webpage, paper explainer, and interactive demonstrator** for this project.
> 2. **Technology Stack:** Strictly **PHP + HTML5 + CSS3 + Vanilla JavaScript**. 
>    - **NO Python virtual environments (`.venv`), Conda, PyTorch, or ML training pipelines belong in this repository.**
> 3. **Model Backend Architecture:**
>    - Deep learning / heavy models are **NEVER hosted on this web server** and are **never running 24/7**.
>    - During live presentations or demonstrations (e.g., talks, conferences, interviews), models are spun up on an **external machine** (local GPU workstation, RunPod, or cloud cluster) and exposed via an external API endpoint (FastAPI / Flask / ngrok).
>    - The web interface connects to this external endpoint dynamically via `config.json` (or the on-page settings modal).
>    - When the external model server is offline, the page automatically presents **precomputed benchmark results and sample cases** so the website is always responsive.
> 4. **Boundary Isolation:** This is an independent repository. NEVER traverse into or seek credentials from the parent portfolio website (`config/.secrets.json`, database credentials).

---

## 1. Quickstart: Commands to Create & Initialize This Project Directory

When creating a new project web explainer from this template, run these exact terminal commands:

### Step 1: Copy Template to Your New Project Directory
From the root of your portfolio:
```bash
# 1. Copy the template folder to your new project slug
cp -r projects/_template projects/reachy-mini-emotional-wellbeing

# 2. Enter the new project folder
cd projects/reachy-mini-emotional-wellbeing
```

---

### Step 2: Initialize Standalone Git Repository
Every collaborative paper page maintains its own clean Git history:
```bash
# 1. Initialize standalone git repository on 'main'
git init -b main

# 2. Stage all initial files
git add .

# 3. Create initial scaffold commit
git commit -m "feat: initialize project webpage and demonstrator scaffold"
```

---

### Step 3: Create Remote GitHub Repository & Push

#### Option A: Using GitHub Web UI
1. Go to [https://github.com/new](https://github.com/new).
2. Set **Repository name** to your `reachy-mini-emotional-wellbeing` (e.g., `stroke-detector` or `vit-feature-analyzer`).
3. Set visibility to **Public** or **Private** (leave README & .gitignore unchecked).
4. Click **Create repository**.
5. Push your local files:
```bash
git remote add origin https://github.com/AaratSatsangi/reachy-mini-emotional-wellbeing.git
git branch -M main
git push -u origin main
```

#### Option B: Using GitHub CLI (`gh` - if installed)
```bash
gh repo create AaratSatsangi/reachy-mini-emotional-wellbeing --public --source=. --remote=origin --push
```

---

### Step 4: Invite Collaborators (Co-authors / Research Partners)
Collaborators invited here will **only have access to this paper/project repository**, keeping your personal website and private secrets completely safe:

- **Via GitHub Web UI:**
  1. Open: `https://github.com/AaratSatsangi/reachy-mini-emotional-wellbeing/settings/access`
  2. Click **"Add people"**.
  3. Enter your collaborator's GitHub username.
  4. Select **"Write"** permission and send the invitation.

- **Via GitHub CLI (`gh`):**
```bash
gh repo collaborator add <collaborator-username> --permission push
```

---

### Step 5: Link into the Main Portfolio as a Git Submodule

To allow your personal website (`aaratsatsangi.in`) to serve this project page under `aaratsatsangi.in/projects/reachy-mini-emotional-wellbeing/`, register it as a submodule:

From the root of your portfolio repository:
```bash
# 1. Return to portfolio root
cd ../..

# 2. Add as a git submodule
git submodule add https://github.com/AaratSatsangi/reachy-mini-emotional-wellbeing.git projects/reachy-mini-emotional-wellbeing

# 3. Commit the submodule pointer to the portfolio
git add .gitmodules projects/reachy-mini-emotional-wellbeing
git commit -m "feat(projects): add reachy-mini-emotional-wellbeing webpage as submodule"
```

*(Alternative: If you do not wish to track it via git submodules, simply add `projects/reachy-mini-emotional-wellbeing` to your root `.gitignore`)*.

---

## 2. Collaborator Guide (Instructions for Co-Authors)

Share these instructions with collaborators working on the webpage copy, figures, or interactive widgets:

### 1. Clone the Standalone Repository
```bash
git clone https://github.com/AaratSatsangi/reachy-mini-emotional-wellbeing.git
cd reachy-mini-emotional-wellbeing
```

### 2. Local Preview (No Python / No Node required)
Use PHP's built-in development server:
```bash
php -S localhost:8000
```
Open [http://localhost:8000](http://localhost:8000) in your browser. Any edits to `index.php`, `css/style.css`, or `js/app.js` will immediately reflect upon page refresh.

### 3. Submitting Changes
```bash
git checkout -b feature/interactive-figure
git add .
git commit -m "docs: add interactive architecture ablation diagram"
git push -u origin feature/interactive-figure
```

---

## 3. Pulling Updates & Syncing Portfolio (For Aarat)

When your collaborator pushes updates to GitHub:
```bash
# 1. Enter the project folder
cd projects/reachy-mini-emotional-wellbeing

# 2. Pull latest commits
git pull origin main

# 3. Update the portfolio submodule pointer:
cd ../..
git add projects/reachy-mini-emotional-wellbeing
git commit -m "chore(projects): sync reachy-mini-emotional-wellbeing to latest commit"
```

---

## 4. Live Model Demonstrations Architecture

When demoing this project live in front of an audience:

1. **Spin up your Model Server:**
   - On your local machine, GPU laptop, or cloud GPU (RunPod/Colab), start your model API (e.g., a lightweight FastAPI or Flask script that takes inputs and returns predictions/heatmaps).
   - If hosting locally, you can expose it securely via an ngrok tunnel (e.g. `ngrok http 8000` -> `https://xyz.ngrok-free.app`).

2. **Configure the Demo Webpage:**
   - Open your project page in the browser (`https://aaratsatsangi.in/projects/reachy-mini-emotional-wellbeing/`).
   - Click the **"Live Server Settings"** gear button on the page.
   - Enter your active API URL (e.g. `https://xyz.ngrok-free.app` or `http://localhost:8000`).
   - Click **Save & Test Connection**.
   - The webpage writes this to `config.json` via `save_config.php` and connects live to your GPU model.

3. **When the Demo Server is Shut Down:**
   - The webpage detects that the external API is offline.
   - It automatically switches to **Precomputed Simulation Mode**, displaying pre-rendered sample cases, heatmaps, and metrics so visitors can still explore the paper results 24/7 without requiring active GPU compute.

---

## 5. Registering the Project in Portfolio CMS

To display this project on the main portfolio page (`aaratsatsangi.in/projects.php`):
1. Navigate to `https://aaratsatsangi.in/admin/` → **Projects** tab.
2. Click **+ Add Project**.
3. Fill in:
   - **Title:** The full title of the published work or project.
   - **Slug:** `reachy-mini-emotional-wellbeing`
   - **Description:** 1-2 sentence TL;DR of the paper and findings.
   - **Demo URL:** `../projects/reachy-mini-emotional-wellbeing/index.php` (or `demo.php`)
   - **Repo URL:** `https://github.com/AaratSatsangi/reachy-mini-emotional-wellbeing`
   - **Paper URL:** Official DOI link or arXiv link.
   - **Thumbnail:** Upload a representative figure or system diagram.
   - **Tags:** e.g., `Published Work, Medical AI, Computer Vision`.
