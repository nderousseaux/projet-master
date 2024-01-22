job "web" {
  datacenters = ["dc1"]

  group "web" {
    count = 5

    network {
      port "web" {
        to = 443
      }
    }

    task "web" {
      driver = "docker"
      config {
        force_pull = true
        image = "registry.app.unistra.fr/fseel/projet-master-23-24/web"
        ports = ["web"]
	volumes = [ "backend:/var/www/html/backend/api", "tmp:/tmp" ]
      }

      template {
	data = "MONGODB_URL=\"mongodb://{{ $services := service \"mongo\" }}{{ range $index, $service := $services }}{{ .Address }}:{{ .Port }}{{ if ne (add $index 1) (len $services) }},{{ end }}{{ end }}\""
	destination = "/var/mongo.env"
	env = true
      }

      template {
        data = "{{ env \"meta.cleAPI\" }}"
	destination = "backend/cleAPI.txt"
      }

      template {
      	data = "SMTP_ID=\"{{ env \"meta.SMTP_ID\" }}\"\nSMTP_PW=\"{{ env \"meta.SMTP_PW\" }}\""
	destination = "tmp/.env"
      }
      
			service {
				name = "web"
				tags = ["urlprefix-/ proto=https tlsskipverify=true"]
				port = "web"

				check {
					name = "check-https"
					type = "http"
					protocol = "https"
					path = "/"
					interval = "10s"
					timeout = "2s"
					tls_skip_verify = true
				}
			}

      resources {
        cpu    = 200
        memory = 256
      }
    }

    scaling {
      enabled = true
      min = 2
      max = 10
    }
	}
}
