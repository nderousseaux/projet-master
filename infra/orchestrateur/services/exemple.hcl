# The full documentation is available here : https://www.nomadproject.io/docs/job-specification/index.html

job "hello-nomad" {
  datacenters = ["dc1"]
  type = "service"

  group "services" {
    count = 1

    task "frontend" {
      driver = "docker"
      config {
        image = "jdxlabs/hello-nomad"

        port_map {
          http = 8080
        }
      }
      
      service {
        port = "http"
      }
      
      resources {
        cpu    = 100 # MHz
        memory = 100 # MB
        
        network {
          mbits = 10
          port "http" {
            #static = 8080
          }
        }
      }
    }
  }
}