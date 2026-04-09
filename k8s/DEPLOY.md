# WeConnect — Docker & Kubernetes Deployment Guide

## Directory Layout

```
.
├── Dockerfile                        # App image definition
├── .dockerignore                     # Files excluded from the image
├── docker-compose.yml                # Local dev stack
├── wecondb_bak.sql                   # Database schema backup
├── app/                              # Application source code
│   ├── index.html                    # Main page
│   ├── *.php                         # PHP scripts
│   ├── *.js                          # JavaScript files
│   └── *.css                         # Stylesheets
├── scripts/                          # Setup and utility scripts
│   ├── weconnect-setup.sh            # Setup script
│   └── weconnect-setup-updated.sh    # Updated setup script
├── docker/
│   ├── apache-weconnect.conf         # Apache virtual host config
│   └── php.ini                       # PHP runtime settings
└── k8s/
    ├── 00-namespace.yaml             # Namespace: weconnect
    ├── 01-secret.yaml                # DB + admin credentials
    ├── 02-pvc.yaml                   # 5 Gi persistent volume for MariaDB
    ├── 03-configmap.yaml             # DB schema SQL (init on first boot)
    ├── 04-db-statefulset.yaml        # MariaDB StatefulSet + headless Service
    ├── 05-app-deployment.yaml        # PHP/Apache Deployment + ClusterIP Service
    ├── 06-ingress.yaml               # Ingress (nginx) — HTTP/HTTPS routing
    ├── 07-hpa.yaml                   # Horizontal Pod Autoscaler
    └── 08-network-policy.yaml        # NetworkPolicies (least-privilege)
```

---

## 1 — Local Development with Docker Compose

```bash
# 1. Create a .env file with your secrets
cat > .env <<EOF
WECON_DB_PASS=supersecretdbpass
WECON_ADMIN_PASS=supersecretadminpass
EOF

# 2. Start the stack
docker compose up -d

# 3. Open http://localhost:8080
```

---

## 2 — Build & Push the Docker Image

```bash
# Build
docker build -t ghcr.io/<your-github-username>/weconnect:latest .

# Push to GitHub Container Registry (or swap for Docker Hub / ECR / GCR)
echo $GITHUB_TOKEN | docker login ghcr.io -u <your-github-username> --password-stdin
docker push ghcr.io/<your-github-username>/weconnect:latest
```

Update the `image:` field in `k8s/05-app-deployment.yaml` to match your registry path.

---

## 3 — Kubernetes Deployment

### Prerequisites

| Tool | Purpose |
|------|---------|
| `kubectl` configured | Access to your cluster |
| `ingress-nginx` controller | External HTTP routing |
| `metrics-server` | Required for HPA |
| `cert-manager` *(optional)* | Automatic TLS via Let's Encrypt |

### 3a — Set Real Secrets

**Never use the placeholder base64 values in production.**

```bash
# Generate proper base64 values
echo -n 'your-real-db-password'    | base64   # → paste into WECON_DB_PASS
echo -n 'your-real-admin-password' | base64   # → paste into WECON_ADMIN_PASS
```

Edit `k8s/01-secret.yaml` with your real values, or use `kubectl create secret`:

```bash
kubectl create secret generic weconnect-secrets \
  --namespace weconnect \
  --from-literal=WECON_DB_PASS='your-real-db-password' \
  --from-literal=WECON_ADMIN_PASS='your-real-admin-password' \
  --from-literal=MYSQL_ROOT_PASSWORD='your-real-db-password'
```

### 3b — Set Your Domain

Edit `k8s/06-ingress.yaml` and replace `weconnect.yourdomain.com` with your actual domain.

### 3c — Apply All Manifests

```bash
# Apply in order (numbers ensure correct dependency order)
kubectl apply -f k8s/00-namespace.yaml
kubectl apply -f k8s/01-secret.yaml
kubectl apply -f k8s/02-pvc.yaml
kubectl apply -f k8s/03-configmap.yaml
kubectl apply -f k8s/04-db-statefulset.yaml
kubectl apply -f k8s/05-app-deployment.yaml
kubectl apply -f k8s/06-ingress.yaml
kubectl apply -f k8s/07-hpa.yaml
kubectl apply -f k8s/08-network-policy.yaml

# Or apply the entire directory at once:
kubectl apply -f k8s/
```

### 3d — Verify

```bash
# Check all pods are Running
kubectl get pods -n weconnect

# Check services
kubectl get svc -n weconnect

# Check ingress (grab the external IP)
kubectl get ingress -n weconnect

# Stream app logs
kubectl logs -n weconnect -l component=app -f

# Stream DB logs
kubectl logs -n weconnect -l component=database -f
```

---

## 4 — Updating the App

```bash
# Build and push new image with a version tag
docker build -t ghcr.io/<you>/weconnect:v1.1.0 .
docker push ghcr.io/<you>/weconnect:v1.1.0

# Rolling update (zero downtime)
kubectl set image deployment/weconnect-app \
  weconnect=ghcr.io/<you>/weconnect:v1.1.0 \
  -n weconnect

# Watch rollout
kubectl rollout status deployment/weconnect-app -n weconnect
```

---

## 5 — Teardown

```bash
# Delete everything in the namespace
kubectl delete namespace weconnect
```

---

## Architecture Diagram

```
Internet
   │
   ▼
┌──────────────────────────┐
│  Ingress (nginx)         │  Port 80/443
│  weconnect.yourdomain.com│
└────────────┬─────────────┘
             │ HTTP :80
             ▼
┌──────────────────────────┐
│  weconnect-app (x2 pods) │  PHP 8.2 + Apache
│  Deployment              │  HPA: 2–10 replicas
└────────────┬─────────────┘
             │ MySQL :3306
             ▼
┌──────────────────────────┐
│  weconnect-db (x1 pod)   │  MariaDB 10.11
│  StatefulSet             │  PVC: 5 Gi
└──────────────────────────┘
```
