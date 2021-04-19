import { Component, OnInit } from '@angular/core';
import { FormBuilder, Validators } from '@angular/forms';
import { CommentService } from 'src/app/core/services/comment.service';
import { SessionService } from 'src/app/core/services/session.service';
import { Comment } from '../../../../core/models/comment';
@Component({
  selector: 'app-comment',
  templateUrl: './comment.component.html',
  styleUrls: ['./comment.component.css']
})
export class CommentComponent implements OnInit {

  constructor(
    private _sessionService: SessionService,
    private _commentService: CommentService,
    private _formBuilder: FormBuilder
    ) { }

  id: number;
  name: string;
  surname1: string;
  surname2: string;
  email: string;
  username: string;
  password: string;
  imgAvatar: FileList;
  aboutMe: string;
  isPrivate: number;

  user = this._sessionService.getCurrentUser();
  token = this._sessionService.getCurrentToken();
  ngOnInit(): void {
    this.id = this.user.id;
    this.name = this.user.name;
    this.surname1 = this.user.surname1;
    this.surname2 = this.user.surname2;
    this.email = this.user.email;
    this.username = this.user.username;
    this.password = this.user.password;
    this.imgAvatar = this.user.imgAvatar;
    this.aboutMe = this.user.aboutMe;
    this.isPrivate = this.user.isPrivate;
  }

  isValidFormSubmitted = null;
  //TODO: Contador de carácteres
  commentForm = this._formBuilder.group({
    bodyComment: ['', [Validators.required, Validators.minLength(1), Validators.maxLength(255)]]
  });

  get bodyComment() { return this.commentForm.get('bodyComment'); }

  postComment() {
    this.isValidFormSubmitted = false;
    if (this.commentForm.invalid) {
      return;
    }
    this.isValidFormSubmitted = true;
    let comment: Comment = this.commentForm.value;
    this._commentService.postComment(comment).subscribe(
      (result) => {

      }, (error) => {
        console.log(error);
    });
  }


}
