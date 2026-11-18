/**
 * Research Project Webpage & Demonstrator Interaction Script
 * - Pings external model server (when running on separate machine during live demos)
 * - Automatically falls back to precomputed results when external server is offline
 * - Allows configuring API URL via live settings modal
 * - Handles 1-click BibTeX copy
 */

document.addEventListener('DOMContentLoaded', () => {
  let apiBaseUrl = (window.PROJECT_CONFIG && window.PROJECT_CONFIG.API_BASE_URL) 
    ? window.PROJECT_CONFIG.API_BASE_URL.replace(/\/+$/, '') 
    : 'http://localhost:8000';

  const apiStatusDot = document.getElementById('apiStatusDot');
  const apiStatusText = document.getElementById('apiStatusText');
  const apiUrlDisplay = document.getElementById('apiUrlDisplay');

  // Modal elements
  const btnOpenApiConfig = document.getElementById('btnOpenApiConfig');
  const btnCloseApiModal = document.getElementById('btnCloseApiModal');
  const apiConfigModal = document.getElementById('apiConfigModal');
  const inputApiUrl = document.getElementById('inputApiUrl');
  const btnSaveApiUrl = document.getElementById('btnSaveApiUrl');
  const saveFeedback = document.getElementById('saveFeedback');

  // Demo elements
  const sampleBtns = document.querySelectorAll('.sample-btn');
  const samplePreview = document.getElementById('samplePreview');
  const btnRunInference = document.getElementById('btnRunInference');
  const resultsArea = document.getElementById('resultsArea');
  const btnCopyBibtex = document.getElementById('btnCopyBibtex');
  const bibtexContent = document.getElementById('bibtexContent');

  let activeSample = 'case_1';
  let isApiOnline = false;

  // Sample data definitions (precomputed fallbacks)
  const sampleData = {
    case_1: {
      name: "Sample Case A",
      label: "Class 0 / Nominal Target",
      confidence: "98.4%",
      latency: "14.2 ms",
      features: "High-density attention concentrated in receptive field (patches 14-22).",
      tags: ["High Confidence", "Nominal"]
    },
    case_2: {
      name: "Sample Case B",
      label: "Class 1 / Perturbed Target",
      confidence: "94.7%",
      latency: "18.6 ms",
      features: "Attention sinks mitigated via register token dynamic allocation.",
      tags: ["Perturbed", "Attention Re-aligned"]
    },
    case_3: {
      name: "Sample Case C",
      label: "Class 2 / Boundary Variant",
      confidence: "89.1%",
      latency: "15.9 ms",
      features: "Latent manifold distance closest to cluster boundary; robust margin preserved.",
      tags: ["Boundary", "Robust Margin"]
    }
  };

  // 1. Check live model endpoint connectivity
  async function checkApiHealth() {
    if (!apiBaseUrl) {
      setApiStatus(false, 'No API URL specified (Simulation Mode)');
      return;
    }

    try {
      const controller = new AbortController();
      const timeoutId = setTimeout(() => controller.abort(), 2000); // 2s timeout
      
      const res = await fetch(`${apiBaseUrl}/health`, {
        method: 'GET',
        signal: controller.signal
      });
      clearTimeout(timeoutId);

      if (res.ok) {
        setApiStatus(true, 'Live Model Server Connected');
      } else {
        setApiStatus(false, 'Live Server Unreachable (Precomputed Simulation Mode)');
      }
    } catch (err) {
      setApiStatus(false, 'Live Server Offline (Precomputed Simulation Mode)');
    }
  }

  function setApiStatus(online, text) {
    isApiOnline = online;
    if (apiStatusDot) {
      apiStatusDot.className = `status-dot ${online ? 'online' : 'offline'}`;
    }
    if (apiStatusText) {
      apiStatusText.innerText = text;
    }
    if (apiUrlDisplay) {
      apiUrlDisplay.innerText = apiBaseUrl || 'Offline';
    }
  }

  // Initial ping
  checkApiHealth();

  // 2. Sample picker switching
  sampleBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      sampleBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      activeSample = btn.dataset.sample;
      
      const info = sampleData[activeSample];
      samplePreview.innerHTML = `
        <div class="preview-box">
          <i class="fa-solid fa-file-lines fa-2x" style="color: var(--color-primary); margin-bottom: 0.5rem;"></i>
          <p style="font-weight: 600;">${info.name}</p>
          <span style="font-size: 0.8rem; color: var(--color-text-muted);">${info.label}</span>
        </div>
      `;
    });
  });

  // 3. Run Inference Simulation / Live Call
  btnRunInference.addEventListener('click', async () => {
    btnRunInference.disabled = true;
    btnRunInference.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Evaluating...';

    if (isApiOnline) {
      // Live API Call to external GPU machine
      try {
        const response = await fetch(`${apiBaseUrl}/predict`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ sample_id: activeSample })
        });
        const data = await response.json();
        renderResults(data.prediction, data.confidence, data.latency || '12.4 ms', true);
      } catch (err) {
        // Fallback if live call fails mid-demo
        renderPrecomputedResults();
      }
    } else {
      // Offline / Precomputed Showcase (smooth 400ms UI response)
      setTimeout(() => {
        renderPrecomputedResults();
      }, 400);
    }
  });

  function renderPrecomputedResults() {
    const info = sampleData[activeSample];
    renderResults(info.label, info.confidence, info.latency, false, info.features);
  }

  function renderResults(prediction, confidence, latency, isLive, featuresText) {
    btnRunInference.disabled = false;
    btnRunInference.innerHTML = '<i class="fa-solid fa-play"></i> Run Model Evaluation';

    const sourceBadge = isLive 
      ? '<span style="background: rgba(16,185,129,0.15); color: #10B981; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 600;">Live GPU API</span>'
      : '<span style="background: rgba(245,158,11,0.15); color: #D97706; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 600;">Precomputed Benchmark</span>';

    resultsArea.innerHTML = `
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <h4 style="margin: 0; font-size: 1rem;">Evaluation Output</h4>
        ${sourceBadge}
      </div>
      <div style="font-family: var(--font-mono); font-size: 0.85rem; line-height: 1.6;">
        <div style="margin-bottom: 0.5rem;"><strong>Predicted Class:</strong> <span style="color: var(--color-primary); font-weight: 600;">${prediction}</span></div>
        <div style="margin-bottom: 0.5rem;"><strong>Confidence Score:</strong> ${confidence}</div>
        <div style="margin-bottom: 0.5rem;"><strong>Inference Latency:</strong> ${latency}</div>
        ${featuresText ? `<div style="margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px dashed var(--color-border); color: var(--color-text-secondary); font-family: var(--font-body); font-size: 0.85rem;"><strong>Representation Analysis:</strong> ${featuresText}</div>` : ''}
      </div>
    `;
  }

  // 4. API Configuration Modal Handlers
  if (btnOpenApiConfig && apiConfigModal) {
    btnOpenApiConfig.addEventListener('click', () => {
      apiConfigModal.style.display = 'flex';
      inputApiUrl.value = apiBaseUrl;
      saveFeedback.style.display = 'none';
    });

    btnCloseApiModal.addEventListener('click', () => {
      apiConfigModal.style.display = 'none';
    });

    apiConfigModal.addEventListener('click', (e) => {
      if (e.target === apiConfigModal) {
        apiConfigModal.style.display = 'none';
      }
    });

    btnSaveApiUrl.addEventListener('click', async () => {
      const newUrl = inputApiUrl.value.trim().replace(/\/+$/, '');
      btnSaveApiUrl.disabled = true;
      btnSaveApiUrl.innerText = 'Saving...';

      try {
        const res = await fetch('save_config.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ api_base_url: newUrl })
        });
        const json = await res.json();

        if (json.success) {
          apiBaseUrl = newUrl;
          window.PROJECT_CONFIG.API_BASE_URL = newUrl;
          saveFeedback.style.display = 'block';
          saveFeedback.style.color = '#10B981';
          saveFeedback.innerText = '✓ Endpoint saved. Testing connectivity...';

          setTimeout(() => {
            apiConfigModal.style.display = 'none';
            btnSaveApiUrl.disabled = false;
            btnSaveApiUrl.innerText = 'Save & Test Connection';
            checkApiHealth();
          }, 800);
        } else {
          throw new Error(json.error || 'Failed to save');
        }
      } catch (err) {
        saveFeedback.style.display = 'block';
        saveFeedback.style.color = '#EF4444';
        saveFeedback.innerText = 'Error saving configuration: ' + err.message;
        btnSaveApiUrl.disabled = false;
        btnSaveApiUrl.innerText = 'Save & Test Connection';
      }
    });
  }

  // 5. 1-Click BibTeX copy
  if (btnCopyBibtex && bibtexContent) {
    btnCopyBibtex.addEventListener('click', () => {
      navigator.clipboard.writeText(bibtexContent.innerText).then(() => {
        const originalText = btnCopyBibtex.innerHTML;
        btnCopyBibtex.innerHTML = '<i class="fa-solid fa-check"></i> Copied!';
        setTimeout(() => {
          btnCopyBibtex.innerHTML = originalText;
        }, 2000);
      });
    });
  }
});
