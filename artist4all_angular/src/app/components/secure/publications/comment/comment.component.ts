import { THIS_EXPR } from '@angular/compiler/src/output/output_ast';
import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute } from '@angular/router';
import { Publication } from 'src/app/core/models/publication';
import { CommentService } from 'src/app/core/services/comment.service';
import { PublicationService } from 'src/app/core/services/publication.service';
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
    private _publicationService: PublicationService,
    private _formBuilder: FormBuilder,
    private _activeRoute: ActivatedRoute,
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


  token = this._sessionService.getCurrentToken();

  publication: Publication;
  comments: Array<Comment>;
  commentForm: FormGroup;
  id_publication: string;

  ngOnInit(): void {
    const user = this._sessionService.getCurrentUser();
    this._activeRoute.paramMap.subscribe((params) => {
      this.id_publication = params.get('id_publication');
      this._commentService.getPublicationComments(user.id, parseInt(this.id_publication)).subscribe(
        (result) => {
          this.comments = result;
        }, (error) => {
          console.log(error);
        });
    });
    this.id = user.id;
    this.name = user.name;
    this.surname1 = user.surname1;
    this.surname2 = user.surname2;
    this.email = user.email;
    this.username = user.username;
    this.password = user.password;
    this.imgAvatar = user.imgAvatar;
    this.aboutMe = user.aboutMe;
    this.isPrivate = user.isPrivate;

    this.commentForm = this._formBuilder.group({
      id: [null],
      user: [user],
      bodyComment: ['', []],
      // bodyComment: ['', [Validators.required, Validators.minLength(1), Validators.maxLength(255)]],
      isEdited: [0],
      comment_date: [null],
      id_publication: [parseInt(this.id_publication)],
      id_comment_reference: [null]
    });

  }

  isValidFormSubmitted = null;
  //TODO: Contador de carácteres


  get bodyComment() { return this.commentForm.get('bodyComment'); }

  newComment: Comment;
  postComment() {
    this.isValidFormSubmitted = false;
    if (this.commentForm.invalid) {
      return;
    }
    this.isValidFormSubmitted = true;
    let comment: Comment = this.commentForm.value;
    this._commentService.postComment(comment).subscribe(
      (result) => {
        this.newComment = result;
        this.commentForm.reset();
      }, (error) => {
        console.log(error);
    });
  }


}
