mercure_executable:
	chmod +x ./mercure/mercure

mercure_server:
	JWT_KEY='kiloukoi_ilo' ADDR='localhost:3000' ALLOW_ANONYMOUS=1 CORS_ALLOWED_ORIGINS="http://localhost:8000" ./mercure/mercure