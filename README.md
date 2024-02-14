# Goal and Ambitions
My goal with this project is to have a working site that can be used as mainly a Markdown-based Content Management System. I want the user-facing site to be simple to modify and to overall not be all too heavy.
In the early stages, I also want to try to keep my Javascript usage at a minimum.

# Roadmap
As outlined below is my roadmap for this project. This is not a timed roadmap a progress-based roadmap divided into stages.

- ***First Release:*** *(in progress)*

  In this first release, I just want to get something working out, a minimally viable product. In this phase, I want to try writing a stable backend with clean code on which I can later build. The front end is not a priority and just has to do the job.
  That means that styling as well as proper HTML structures are not something I care much about at the moment, as they can easily be implemented later on when I have come up with a proper design.
  The tasks required to reach this milestone are the following:
  - admin panel
  - create and view Pages
  - very simple user system
  - basic documentation

- ***Frontend cleanup***

  In this phase, I want to create a satisfying interface for the user. My main focus will be to create a design for the page and to create proper HTML structures with my PHP output.
  The tasks required to reach this milestone are the following:
  - User-facing interface
  - Admin Panel usability - make items more readable and make a better UI
 
- ***Performance***
  
  In this stage, I want to try to make the page more performant using [Redis](https://redis.io/) as a server-side caching mechanism. I will also try looking into other methods for improving the performance of the server like indexing.
  The tasks required to reach this milestone are the following:
  - Implement Redis
  - possibly implementing other performance-enhancing methods
 
After these initial milestones, the goal will be to improve the site as a whole. This mostly means implementing features like search, comments, as well as moderation.
