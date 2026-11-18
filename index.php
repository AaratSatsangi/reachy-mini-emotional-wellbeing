<?php
// =============================================================================
// Project Webpage & Demonstrator
// Explains a published research work, paper, or system.
// Backend is pure PHP; external ML models are accessed via API when demoed live.
// =============================================================================

@include_once __DIR__ . '/track_visits.php';

// Read API configuration (points to external machine running the model during live demos)
$configFile = __DIR__ . '/config.json';
$apiBaseUrl = 'http://localhost:8000';
if (file_exists($configFile)) {
    $configData = json_decode(file_get_contents($configFile), true);
    if (!empty($configData['api_base_url'])) {
        $apiBaseUrl = $configData['api_base_url'];
    }
}

// Project & Paper Metadata (Customized per project)
$projectTitle = "Reaching for Mental Wellbeing via Reachy Mini";
$paperVenue   = "Conference / Journal (e.g. CVPR 2026, ACM, IEEE, arXiv)";
$paperDoi     = "https://doi.org/";
$paperPdf     = "https://arxiv.org/abs/";
$repoUrl      = "https://github.com/AaratSatsangi/reachy-mini-emotional-wellbeing";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($projectTitle); ?> | Research Project</title>
  
  <!-- Typography & Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <!-- Project Stylesheet -->
  <link rel="stylesheet" href="css/style.css">
  
  <script>
    window.PROJECT_CONFIG = {
      API_BASE_URL: "<?php echo htmlspecialchars($apiBaseUrl, ENT_QUOTES, 'UTF-8'); ?>"
    };
  </script>
</head>
<body>

  <!-- Top Navigation Bar -->
  <header class="demo-topbar">
    <a href="../../projects.php" class="back-link">
      <i class="fa-solid fa-arrow-left"></i> Back to Projects Hub
    </a>
    <span class="project-tagline">
      Published Research Project | Aarat Satsangi &amp; Collaborators
    </span>
  </header>

  <main class="page-container">
    
    <!-- Paper Header & Authors -->
    <header class="paper-header">
      <div class="venue-badge"><?php echo htmlspecialchars($paperVenue); ?></div>
      <h1 class="paper-title"><?php echo htmlspecialchars($projectTitle); ?></h1>
      
      <div class="authors-list">
        <span class="author"><a href="https://aaratsatsangi.in" target="_blank">Aarat Satsangi</a><sup>1</sup></span>
        <span class="author">Collaborator One<sup>2</sup></span>
        <span class="author">Collaborator Two<sup>1</sup></span>
      </div>
      <div class="affiliations">
        <span><sup>1</sup>Primary Institution</span> &bull; <span><sup>2</sup>Collaborating Laboratory</span>
      </div>

      <!-- Action Links -->
      <div class="paper-links">
        <a href="<?php echo htmlspecialchars($paperPdf); ?>" target="_blank" class="btn btn-outline">
          <i class="fa-solid fa-file-pdf"></i> Read Paper (PDF)
        </a>
        <a href="<?php echo htmlspecialchars($repoUrl); ?>" target="_blank" class="btn btn-outline">
          <i class="fa-brands fa-github"></i> Source Code
        </a>
        <a href="#interactive-demo" class="btn btn-primary">
          <i class="fa-solid fa-play"></i> Try Interactive Demo
        </a>
        <?php if (!empty($paperDoi) && $paperDoi !== 'https://doi.org/'): ?>
        <a href="<?php echo htmlspecialchars($paperDoi); ?>" target="_blank" class="btn btn-outline">
          <i class="fa-solid fa-link"></i> DOI
        </a>
        <?php endif; ?>
      </div>
    </header>

    <!-- Abstract / Summary -->
    <section class="paper-section card">
      <h2><i class="fa-solid fa-bookmark"></i> Abstract &amp; Key Findings</h2>
      <p class="abstract-text">
        Provide a concise overview of the core problem, intellectual questions, and methodology. 
        Explain what makes this approach novel compared to prior literature, and summarize the key empirical findings.
      </p>
      
      <div class="key-takeaways">
        <div class="takeaway-card">
          <i class="fa-solid fa-bolt"></i>
          <h4>Core Insight</h4>
          <p>Highlight the main theoretical or empirical discovery made in this published work.</p>
        </div>
        <div class="takeaway-card">
          <i class="fa-solid fa-chart-line"></i>
          <h4>Benchmark Performance</h4>
          <p>Key quantitative metric (e.g. +4.2% accuracy improvement, 3x latency reduction).</p>
        </div>
        <div class="takeaway-card">
          <i class="fa-solid fa-cube"></i>
          <h4>Artifact &amp; Models</h4>
          <p>Open-source models, interactive visualizer, and reproducible experimental pipeline.</p>
        </div>
      </div>
    </section>

    <!-- Methodology & System Architecture -->
    <section class="paper-section card">
      <h2><i class="fa-solid fa-diagram-project"></i> Method &amp; Architecture Overview</h2>
      <p>
        Explain the model architecture or procedural pipeline. Include high-resolution vector diagrams or architecture figures below.
      </p>
      <div class="figure-container">
        <div class="figure-placeholder">
          <i class="fa-solid fa-network-wired fa-2x" style="color: var(--color-primary); margin-bottom: 0.5rem;"></i>
          <p>Place architecture diagram or overview figure here (`assets/architecture.png`)</p>
        </div>
        <figcaption class="figure-caption">
          <strong>Figure 1:</strong> System architecture overview illustrating feature extraction, representation alignment, and downstream evaluation.
        </figcaption>
      </div>
    </section>

    <!-- Interactive Demonstration Section -->
    <section id="interactive-demo" class="paper-section card demo-section">
      <div class="demo-section-header">
        <div>
          <h2><i class="fa-solid fa-flask"></i> Interactive Demonstration</h2>
          <p class="section-desc">
            Test the pipeline interactively. When demoed live, connects to the model API running on our GPU machine.
          </p>
        </div>
        
        <!-- Live Endpoint Connection Modal Trigger -->
        <button id="btnOpenApiConfig" class="btn btn-sm btn-secondary" title="Configure Live Demo API Endpoint">
          <i class="fa-solid fa-gear"></i> Live Server Settings
        </button>
      </div>

      <!-- Live API Status Indicator -->
      <div class="api-status-bar">
        <div class="status-indicator">
          <span id="apiStatusDot" class="status-dot"></span>
          <span id="apiStatusText">Checking live model endpoint...</span>
        </div>
        <span class="api-url-badge" id="apiUrlDisplay"><?php echo htmlspecialchars($apiBaseUrl); ?></span>
      </div>

      <!-- Demo Interaction Area -->
      <div class="demo-grid">
        <div class="demo-panel">
          <h3>1. Select or Upload Input Sample</h3>
          <div class="sample-picker">
            <button class="sample-btn active" data-sample="case_1">Sample Case A</button>
            <button class="sample-btn" data-sample="case_2">Sample Case B</button>
            <button class="sample-btn" data-sample="case_3">Sample Case C</button>
          </div>
          
          <div class="sample-preview" id="samplePreview">
            <div class="preview-box">
              <i class="fa-regular fa-image fa-2x" style="color: var(--color-text-muted);"></i>
              <p>Sample Case A selected</p>
            </div>
          </div>

          <button id="btnRunInference" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
            <i class="fa-solid fa-play"></i> Run Model Evaluation
          </button>
        </div>

        <div class="demo-panel">
          <h3>2. Model Predictions &amp; Explanations</h3>
          <div id="resultsArea" class="results-box">
            <div class="empty-state">
              <p>Click "Run Model Evaluation" to test against precomputed results or the live GPU API.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- BibTeX Citation -->
    <section class="paper-section card">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
        <h2 style="margin: 0;"><i class="fa-solid fa-quote-left"></i> Citation</h2>
        <button id="btnCopyBibtex" class="btn btn-sm btn-secondary">
          <i class="fa-regular fa-copy"></i> Copy BibTeX
        </button>
      </div>
      <pre class="bibtex-box"><code id="bibtexContent">@article{satsangi2026project,
  title={<?php echo htmlspecialchars($projectTitle); ?>},
  author={Satsangi, Aarat and Collaborators},
  journal={<?php echo htmlspecialchars($paperVenue); ?>},
  year={2026},
  url={<?php echo htmlspecialchars($paperPdf); ?>}
}</code></pre>
    </section>

  </main>

  <!-- Live API Configuration Modal -->
  <div id="apiConfigModal" class="modal-overlay" style="display: none;">
    <div class="modal-card">
      <div class="modal-header">
        <h3><i class="fa-solid fa-server"></i> Live Demo Model Endpoint</h3>
        <button id="btnCloseApiModal" class="btn-close">&times;</button>
      </div>
      <div class="modal-body">
        <p style="font-size: 0.9rem; color: var(--color-text-secondary); margin-bottom: 1rem;">
          When presenting live, start your model inference server (e.g., FastAPI on RunPod, local GPU, or ngrok tunnel) and enter the URL below:
        </p>
        <label for="inputApiUrl" style="font-size: 0.85rem; font-weight: 600;">Model API Base URL</label>
        <input type="text" id="inputApiUrl" value="<?php echo htmlspecialchars($apiBaseUrl); ?>" placeholder="http://localhost:8000 or https://xyz.ngrok-free.app" class="form-input">
        <div id="saveFeedback" style="font-size: 0.85rem; margin-top: 0.5rem; display: none;"></div>
      </div>
      <div class="modal-footer">
        <button id="btnSaveApiUrl" class="btn btn-primary">Save &amp; Test Connection</button>
      </div>
    </div>
  </div>

  <footer class="page-footer">
    <p>&copy; <?php echo date('Y'); ?> Aarat Satsangi &amp; Research Collaborators. Hosted on <a href="https://aaratsatsangi.in">aaratsatsangi.in</a>.</p>
  </footer>

  <script src="js/app.js"></script>
</body>
</html>
