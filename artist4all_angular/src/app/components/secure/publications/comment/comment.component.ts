import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute } from '@angular/router';
import { Publication } from 'src/app/core/models/publication';
import { User } from 'src/app/core/models/user';
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

  user: User;
  token = this._sessionService.getCurrentToken();

  publication: Publication;
  comments: Array<Comment>;
  commentForm: FormGroup;
  id_publication: string = "";
  ngOnInit(): void {
    this.user = this._sessionService.getCurrentUser();
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
    this._activeRoute.paramMap.subscribe((params) => {
    this.id_publication = params.get('id_publication');
      this._commentService.getPublicationComments(this.id, parseInt(this.id_publication)).subscribe(
      (result) => {
        this.comments = result;
      }, (error) => {
        console.log(error);
      });
    });
    this.commentForm = this._formBuilder.group({
      id: [null],
      user: [this.user],
      bodyComment: ['', []],
      // bodyComment: ['', [Validators.required, Validators.minLength(1), Validators.maxLength(255)]],
      isEdited: [0],
      comment_date: [null],
      id_publication: [parseInt(this.id_publication)],
      id_comment_reference: [null]
    });
  }


  showingSubcommentForm: boolean = false;
  toggleSubcommentResponseForm(index: number): void {
    let formSubcommentResponseForm = document.getElementById(index + 'formSubcommentResponseForm');
    if (!this.showingSubcommentForm) {
      formSubcommentResponseForm.style.display = 'block';
      this.showingSubcommentForm = true;
    } else {
      formSubcommentResponseForm.style.display = 'none';
      this.showingSubcommentForm = false;
    }
  }

  showingForm: boolean = false;
  toggleResponseForm(index: number): void {
    let formResponseContainer = document.getElementById(index + 'formResponseContainer');
    let subcommentContainer = document.getElementById(index + 'subcommentContainer');
    if (!this.showingForm) {
      formResponseContainer.style.display = 'block';
      subcommentContainer.style.display = 'none';
      this.showingForm = true;
      this.showingSubcomments = false;
    } else {
      formResponseContainer.style.display = 'none';
      this.showingForm = false;
    }
  }

  showingSubcomments: boolean = false;
  toggleSubcomments(index: number) {
    let formResponseContainer = document.getElementById(index + 'formResponseContainer');
    let subcommentContainer = document.getElementById(index + 'subcommentContainer');
    if (!this.showingSubcomments) {
      subcommentContainer.style.display = 'block';
      formResponseContainer.style.display = 'none';
      this.showingSubcomments = true;
      this.showingForm = false;
    } else {
      subcommentContainer.style.display = 'none';
      this.showingSubcomments = false;
    }
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
        this.comments.unshift(this.newComment);
        this.commentForm.reset();
        this.commentForm.controls['user'].setValue(this.user);
        this.commentForm.controls['id_publication'].setValue(parseInt(this.id_publication));
        this.commentForm.controls['isEdited'].setValue(0);
        this.commentForm.controls['id_comment_reference'].setValue(null);
      }, (error) => {
        console.log(error);
    });
  }

}
