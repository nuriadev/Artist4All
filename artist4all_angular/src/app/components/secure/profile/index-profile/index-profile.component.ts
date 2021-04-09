import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { SessionService } from 'src/app/core/services/session.service';
import { UserService } from 'src/app/core/services/user.service';
import { NgxSpinnerService } from "ngx-spinner";
import { PublicationService } from 'src/app/core/services/publication.service';
import { Publication } from 'src/app/core/models/publication';
import { User } from 'src/app/core/models/user';
@Component({
  selector: 'app-profile',
  templateUrl: './index-profile.component.html',
  styleUrls: ['./index-profile.component.css'],
  providers: [UserService]
})
export class ProfileComponent implements OnInit {

  constructor(
    private _sessionService: SessionService,
    private _userService: UserService,
    private _publicationService: PublicationService,
    private _activeRoute: ActivatedRoute,
    private spinner: NgxSpinnerService
  ) { }

  publications:Array<Publication> = [];

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
  n_followed:number;

  profileUsername:string = "";
  isMyProfile:boolean = false;
  loaded: boolean;
  ngOnInit(): void {
    this.loaded = false;
    this._activeRoute.paramMap.subscribe(
      (params) => {
        this.spinner.show();
        setTimeout(() => {
          this.spinner.hide();
          this.loaded = true;
        }, 1200);
        this.profileUsername = params.get('username');
        if (this.profileUsername == 'my') {
          setTimeout(() => {
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
            this.getFollowersAndFollowed(this.username, this.token);
            this.getUserPublications(this.username, this.token);
          }, 1200);
        } else {
          this._userService.getUserByUsername(this.profileUsername).subscribe(
            (result) => {
              this.id = result.id,
              this.name = result.name,
              this.surname1 = result.surname1,
              this.surname2 = result.surname2,
              this.email = result.email,
              this.username = result.username,
              this.imgAvatar = result.imgAvatar,
              this.aboutMe = result.aboutMe
              this.isMyProfile = false;
              this._userService.isFollowingThatUser(this.user.username, this.username, this.token).subscribe(
                (result) => {
                  if (result != null) {
                    this.id_follow = result['id_follow'];
                    this.isFollowed = true;
                  } else {
                    this.isFollowed = false;
                  }
                }, (error) => {
                  console.log(error);
                }
              )
              this.getFollowersAndFollowed(this.username, this.token);
              this.getUserPublications(this.username, this.token);

            }, (error) => {
              console.log(error);
            }
          )
        }
      }
    );
  }

  deletePublication(index:number) {
    this._publicationService.delete(this.publications[index].id, this.token).subscribe(
      (result) => {
        location.reload();
      }, (error) => {
        console.log(error);
      }
    )
  }

  n_likes:number;
  n_comments:number;
  id_like:number;
  likePublication(index:number) {
    this.isLiked = true;
    this.n_likes++;
    this._publicationService.addLike(this.publications[index], this.user.id, this.username, this.token).subscribe(
      (result) => {
        this.id_like = result;
      }, (error) => {
        console.log(error);
      }
    )
  }

  removeLikePublication(index:number) {
    this.isLiked = false;
    this.n_likes--;
    this._publicationService.removelike(this.publications[index], this.username, this.token).subscribe()
  }

  isLiked:boolean;
  isPublicationLiked(index:number) {
    let likeIcon = document.getElementById('likeIcon' + index);
    let notLikedIcon = document.getElementById('notLikedIcon' + index);
    if (!this.isLiked) {
      likeIcon.style.display = 'block';
      notLikedIcon.style.display = 'none';
      this.isLiked = true;
    } else {
      notLikedIcon.style.display = 'block';
      likeIcon.style.display = 'none';
      this.isLiked = false;
    }
  }

  followUser() {
    this.isFollowed = true;
    this.n_followers++;
    this._userService.followUser(this.user.username, this.username, this.token).subscribe(
      (result) => {
        this.id_follow = result['id_follow'];
      }, (error) => {
        console.log(error);
      }
    )
  }

  id_follow:number;
  unfollowUser() {
    this.isFollowed = false;
    this.n_followers--;
    this._userService.unfollowUser(this.user.username, this.username, this.id_follow, this.token).subscribe();

  }

  isFollowed:boolean;
  isUserFollowed() {
    let followContainer = document.getElementById('followContainer');
    let unfollowContainer = document.getElementById('unfollowContainer');
    if (!this.isFollowed) {
      followContainer.style.display = 'block';
      unfollowContainer.style.display = "none";
      this.isFollowed = true;
    } else {
      unfollowContainer.style.display = 'block';
      followContainer.style.display = 'none';
      this.isFollowed = false;
    }
  }

  getFollowersAndFollowed(username:string, token:string) {
    this._userService.countFollowers(username, token).subscribe(
      (result) => {
        this.n_followers = result['n_followers'];
      }, (error) => {
        console.log(error);
      }
    )
    this._userService.countFollowed(username, token).subscribe(
      (result) => {
        this.n_followed = result['n_followed'];
      }, (error) => {
        console.log(error);
      }
    )
  }

  getUserPublications(username:string, token:string) {
    this._publicationService.getUserPublications(username, token).subscribe(
      (result) => {
        if (result != null) {
          result.forEach(element => {
            element.upload_date = this.adaptDateOfPublication(element.upload_date);
          });
        }
        this.publications = result;
      }, (error) => {
        console.log(error);
      }
    )
  }

  adaptDateOfPublication(upload_date:string) {
    const days = [
      'Domingo',
      'Lunes',
      'Martes',
      'Miércoles',
      'Jueves',
      'Viernes',
      'Sábado',
    ];
    const months = [
      "Enero",
      "Febrero",
      "Marzo",
      "Abril",
      "Mayo",
      "Junio",
      "Julio",
      "Agosto",
      "Septiembre",
      "Octubre",
      "Noviembre",
      "Diciembre"
    ];
    let numberDay = new Date(upload_date).getDay();
    let nameDay = days[numberDay];
    let nameMonth = months[new Date().getMonth()];
    let datetime = upload_date.split(' ');
    let date = datetime[0].split('-');
    let time = datetime[1].split(':');
    let year = date[0];
    let month = date[1];
    let day = date[2];
    let hourAndMin = time[0] + ':' + time[1];
    let adaptedDate = nameDay + ', ' + day + ' de ' + nameMonth + ' de ' + year + ', a las ' + hourAndMin;
    return adaptedDate;
  }
}
