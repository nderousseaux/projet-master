job "sftp" {
  datacenters = ["dc1"]

  group "sftp" {
    count = 1

    network {
      port "sftp" {
        to = 22
        static = 2222
        host_network = "public"
      }
    }
    task "sftp" {
      driver = "docker"
      // docker run -p 2222:22 -v <full_path>/upload:/home/sftp_user/upload -d atmoz/sftp sftp_user:pass:1001
      config {
        force_pull = true
        image = "atmoz/sftp"
        ports = ["sftp"]
        args = [
          "sftp_user:pass:1001"
        ]
        # Volume
        volumes = [
          "/upload:/home/sftp_user/upload"
        ]
      }
      
			service {
				name = "sftp"
				port = "sftp"
				check {
					name     = "alive"
					type     = "tcp"
					interval = "10s"
					timeout  = "2s"

				}
			}

      resources {
        cpu    = 200
        memory = 256
      }
    }

    task "insert" {
      driver = "docker"
      config {
        force_pull = true
        image = "registry.app.unistra.fr/fseel/projet-master-23-24/sftp_updater"

        volumes = [
          "/upload:/upload"
        ]
      }

      template {
	data = "MONGODB_URL=\"mongodb://{{ $services := service \"mongo\" }}{{ range $index, $service := $services }}{{ .Address }}:{{ .Port }}{{ if ne (add $index 1) (len $services) }},{{ end }}{{ end }}\""
	destination = "/mongo.env"
	env = true
      }
    
      resources {
        cpu    = 200
        memory = 256
      }
    }
	}
}
