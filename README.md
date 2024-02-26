# Goal
With this project, I am trying to improve my knowledge in Object Oriented Programming and improve my strategies on how to tackle a project.

Throughout this project, I have learned how I can use OOP, but have also had the opportunity to make  many mistakes, mostly on the planning side of things. I for example didn't set clear goals from the beginning and just started to go. Therefore I later faced the problem of not knowing how I should do something for the reason I didn't know if I wanted to add x feature later. This could easily have been mitigated by setting clear goals for what I want my end product to be. Furthermore, I didn't give much thought to how I should structure my code but started coding away. An example of this is the class working as a template for all pages to build upon. I didn't consider how I should render all the pages from the beginning but later saw that I didn't want to have to change every single page if I wanted to make a change to the page meaning I had to change every page to use the class to construct its content.

# Plan
As stated in my goal I don't want to create the perfect Content Management System, this means I am done with this project once I feel I have learned and done what I wanted to do. But I set these points out as general goals I want to achieve.

1. A minimal viable product
  - [x] create users
  - [x] create pages
  - [x] see available pages
  - [x] view pages
2. Usability
  - [ ] usable interface
  - [x] acceptable page design
3. Add features (Optional)
  - [ ] Users can edit their pages
  - [ ] search filters
  - [x] redis
  - [ ] interface for users
  - [ ] see history of Pages

# How to install
Clone the repository using `git clone https://github.com/JOSCHLINER/Markdown-CMS`. This project is built with docker meaning docker has to be installed on the system. Here on how to install docker: https://docs.docker.com/engine/install/. To start the application go inside the build container of the repo and run `docker compose up -d`. The page should now be up and running on localhost port 80.
