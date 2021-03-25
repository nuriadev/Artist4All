import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { User } from 'src/app/core/models/user';
import { AuthenticationService } from 'src/app/core/services/authentication.service';
import { SessionService } from 'src/app/core/services/session.service';
import { UserService } from 'src/app/core/services/user.service';
@Component({
  selector: 'app-profile',
  templateUrl: './profile.component.html',
  styleUrls: ['./profile.component.css'],
  providers: [UserService]
})
export class ProfileComponent implements OnInit {

  constructor(
    private _sessionService: SessionService,
    private _activeRoute: ActivatedRoute,
    private _userService: UserService
  ) { }

  user = this._sessionService.getCurrentUser();
  token = this._sessionService.getCurrentToken();
  id:number;
  name:string;
  surname1:string;
  surname2:string;
  email:string;
  username:string;
  password:string;
  imgAvatar:FileList;
  aboutMe:string;
  n_followers:number;

  profileUsername:string = "";
  isMyProfile:boolean = false;
  ngOnInit(): void {
    this._activeRoute.paramMap.subscribe(
      (params) => {
        this.profileUsername = params.get("username");
        if (this.profileUsername == 'my') {
          this.id = this.user.id;
          this.name = this.user.name;
          this.surname1 = this.user.surname1;
          this.surname2 = this.user.surname2;
          this.email = this.user.email;
          this.username = this.user.username;
          this.password = this.user.password;
          this.imgAvatar = this.user.imgAvatar;
          this.aboutMe = this.user.aboutMe;
          this.isMyProfile = true;
        } else {
          this._userService.getUserByUsername(this.profileUsername).subscribe(
            (result) => {
                this.id = result['id'],
                this.name = result['name'],
                this.surname1 = result['surname1'],
                this.surname2 = result['surname2'],
                this.email = result['email'],
                this.username = result['username'],
                this.imgAvatar = result['imgAvatar'],
                this.aboutMe = result['aboutMe']
                this.isMyProfile = false;
            }, (error) => {
              console.log(error);
            }
          )
        }
      }
    );

  }

  isFollowed: boolean = false;
  isUserFollowed() {
    let followMessage = document.getElementById('followMessage');
    let followIcon = document.getElementById('followIcon');
    let unfollowIcon = document.getElementById('unfollowIcon');
    if (!this.isFollowed) {
      // todo falta que tenga constancia de si lo seguía de antes por db, tabla follower_followed
      // ! No funcional
      followIcon.style.display = "block";
      unfollowIcon.style.display = "none";
      followMessage.innerHTML = "Seguir";
      this.isFollowed = true;
    } else {
      unfollowIcon.style.display = "block";
      followIcon.style.display = "none";
      followMessage.innerHTML = "Dejar de seguir";
      this.isFollowed = false;
    }
  }
}
