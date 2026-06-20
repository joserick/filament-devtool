FROM sail-8.5/app

# Override Sail's default artisan serve with Orchestra Workbench's testbench serve

ENV SUPERVISOR_PHP_USER="sail"
ENV SUPERVISOR_PHP_COMMAND="composer run serve"

COPY docker/start-container /usr/local/bin/start-container
RUN chmod +x /usr/local/bin/start-container

EXPOSE 80/tcp

ENTRYPOINT ["start-container"]