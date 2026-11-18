# Reaching for Mental Wellbeing via Reachy Mini (Paper & Interactive Explainer Webpage)

[![PHP 8.1+](https://img.shields.io/badge/PHP-8.1+-blue.svg)](https://www.php.net/)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

> **About this repository:**
> This repository contains the official project webpage, paper breakdown, and interactive demonstrator for the published research paper: **"Project Title"**.
> 
> Hosted at: [aaratsatsangi.in/projects/reachy-mini-emotional-wellbeing](https://aaratsatsangi.in/projects/reachy-mini-emotional-wellbeing)

---

## Architecture Overview

- **Stack:** Strictly PHP + HTML5 + CSS3 (Token-aware) + Vanilla JavaScript.
- **Model Hosting Policy:** Deep learning models are **not** hosted on the shared web server. During live presentations or interactive demonstrations, the web interface connects to an external model API running on a local GPU machine, workstation, or cloud cluster (e.g. FastAPI / Flask / RunPod / ngrok). When the external server is offline, the page provides precomputed benchmark simulations.
- **Local Testing:** Run with built-in PHP server:
  ```bash
  php -S localhost:8000
  ```

---

## Directory Structure

```
.
├── AGENT_README.md      # Standalone guidelines for AI coding agents & collaborators
├── README.md            # Public repository documentation
├── index.php            # Main research paper explainer & interactive UI
├── demo.php             # Demo entrypoint alias
├── config.json          # Configurable external model API base URL
├── save_config.php      # Live settings endpoint to update API URL
├── track_visits.php     # Self-contained page visit counter
├── css/
│   └── style.css        # Scoped styles (inherits portfolio theme or falls back)
└── js/
    └── app.js           # Live API pinging, simulation fallbacks, BibTeX copy
```

---

## Collaboration & Contributing

Collaborators are invited directly to this repository to collaborate on the webpage, explanatory text, interactive visualizations, and paper artifacts.

1. Clone this repository:
   ```bash
   git clone https://github.com/AaratSatsangi/reachy-mini-emotional-wellbeing.git
   cd reachy-mini-emotional-wellbeing
   ```
2. Start local PHP preview server:
   ```bash
   php -S localhost:8000
   ```
   Open `http://localhost:8000` in your browser.
3. Create a branch and submit a PR:
   ```bash
   git checkout -b feature/explain-attention-maps
   git commit -m "docs: add attention visualization breakdown"
   git push -u origin feature/explain-attention-maps
   ```

---

## Citation

```bibtex
@article{satsangi2026project,
  title={Project Title Here},
  author={Satsangi, Aarat and Collaborators},
  journal={Venue Name},
  year={2026}
}
```
